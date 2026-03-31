#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# prod.sh — Deploy de producción con backup automático previo
#
# Uso: sudo bash scripts/deploy/prod.sh
#
# Flujo:
#   1. git pull (actualizar código)
#   2. Backup de la BD antes de cualquier cambio
#   3. docker pull (imagen nueva desde GHCR)
#   4. Recrear solo el contenedor odoo (sin bajar DB ni Traefik)
#   5. Health-check con retry
#   6. Notificación webhook (opcional)
# ─────────────────────────────────────────────────────────────────────────────
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"
ENV_FILE="$PROJECT_DIR/.env"

# Valores por defecto (sobreescribibles desde .env)
ODOO_IMAGE="${ODOO_IMAGE:-ghcr.io/jpvargassoruco/catastro:latest}"
BACKUP_DIR="${BACKUP_DIR:-/var/backups/catastro}"
BACKUP_RETENTION="${BACKUP_RETENTION:-7}"
POSTGRES_DB="${POSTGRES_DB:-catastro}"
POSTGRES_USER="${POSTGRES_USER:-odoo}"
DEPLOY_WEBHOOK_URL="${DEPLOY_WEBHOOK_URL:-}"

# Cargar .env si existe
if [[ -f "$ENV_FILE" ]]; then
    set -o allexport
    source "$ENV_FILE"
    set +o allexport
fi

log()    { echo "$(date '+%Y-%m-%d %H:%M:%S') ℹ  $*"; }
ok()     { echo "$(date '+%Y-%m-%d %H:%M:%S') ✅ $*"; }
warn()   { echo "$(date '+%Y-%m-%d %H:%M:%S') ⚠️  $*"; }
err()    { echo "$(date '+%Y-%m-%d %H:%M:%S') ❌ $*" >&2; exit 1; }

notify() {
    local msg="$1"
    if [[ -n "$DEPLOY_WEBHOOK_URL" ]]; then
        curl -sf -X POST -H 'Content-type: application/json' \
            --data "{\"text\":\"$msg\"}" "$DEPLOY_WEBHOOK_URL" || true
    fi
}

# ── 0. Verificación de root ───────────────────────────────────────────────────
[[ "$(id -u)" -eq 0 ]] || err "Este script requiere ejecutarse como root (sudo)."

cd "$PROJECT_DIR"

log "🚢 Iniciando deploy de PRODUCCIÓN de Catastro..."
notify "🚀 Deploy Catastro iniciado en $(hostname) por $USER"

# ── 1. Actualizar código fuente ───────────────────────────────────────────────
log "📥 git pull origin main..."
git pull origin main

# ── 2. Backup de la base de datos ─────────────────────────────────────────────
log "💾 Creando backup de la BD '$POSTGRES_DB'..."
TIMESTAMP="$(date '+%Y%m%d_%H%M%S')"
BACKUP_FILE="$BACKUP_DIR/${POSTGRES_DB}_${TIMESTAMP}.dump"
mkdir -p "$BACKUP_DIR"

DB_CONTAINER="$(docker compose ps -q db 2>/dev/null || docker compose ps -q postgres 2>/dev/null || true)"
if [[ -z "$DB_CONTAINER" ]]; then
    warn "Contenedor de BD no encontrado — omitiendo backup."
else
    docker exec "$DB_CONTAINER" \
        pg_dump -U "$POSTGRES_USER" -Fc "$POSTGRES_DB" > "$BACKUP_FILE"
    log "   Backup guardado en: $BACKUP_FILE"

    # Limpiar backups antiguos (retención)
    find "$BACKUP_DIR" -name "${POSTGRES_DB}_*.dump" \
        -printf '%T@ %p\n' | sort -n | head -n -"$BACKUP_RETENTION" \
        | awk '{print $2}' | xargs -r rm --
    log "   Backups anteriores: retención=$BACKUP_RETENTION"
fi

# ── 3. Pull de la imagen nueva ────────────────────────────────────────────────
log "📦 Descargando imagen $ODOO_IMAGE ..."
docker pull "$ODOO_IMAGE"

# ── 4. Recrear contenedor Odoo sin tocar DB ni Traefik ───────────────────────
log "🔄 Recreando contenedor Odoo..."
docker compose up -d --no-deps --force-recreate odoo

# ── 5. Detectar error de red y hacer restart completo si es necesario ─────────
sleep 10
if docker compose logs --tail=20 odoo 2>&1 | grep -qE "could not translate host name|name resolution"; then
    warn "Error de red detectado — realizando restart completo del stack..."
    docker compose down
    docker compose up -d
    sleep 20
fi

# ── 6. Health-check (máx. 90s) ───────────────────────────────────────────────
log "⏳ Esperando que Odoo esté disponible (máx 90s)..."
OK=0
for i in $(seq 1 18); do
    if curl -sf http://127.0.0.1 > /dev/null 2>&1; then
        OK=1; break
    fi
    echo "   ... ${i}/18 (${i}x5s)"
    sleep 5
done

echo ""
echo "📋 Contenedores activos:"
docker compose ps

echo ""
if [[ "$OK" -eq 1 ]]; then
    ok "Deploy PRODUCCIÓN completado — Odoo disponible en http://catastro.local"
    notify "✅ Deploy Catastro completado en $(hostname) — Odoo disponible"
else
    warn "Deploy completado pero Odoo no respondió en 90s — revisa los logs:"
    echo "  docker compose logs --tail=50 odoo"
    notify "⚠️ Deploy Catastro en $(hostname): Odoo no respondió en 90s"
    exit 1
fi
