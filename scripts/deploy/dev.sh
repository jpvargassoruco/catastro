#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# dev.sh — Deploy rápido en entorno de desarrollo (con hot-reload de addons)
#
# Uso: bash scripts/deploy/dev.sh [--reset]
#   --reset: borra y vuelve a crear la base de datos (útil para empezar limpio)
#
# Requisitos:
#   - Docker y Docker Compose instalados
#   - Red externa 'web-proxy' creada: docker network create web-proxy
#   - /etc/hosts conteniendo: 127.0.0.1 catastro.local
# ─────────────────────────────────────────────────────────────────────────────
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"
ENV_FILE="$PROJECT_DIR/.env"

# Cargar variables del .env si existe
if [[ -f "$ENV_FILE" ]]; then
    set -o allexport
    source "$ENV_FILE"
    set +o allexport
fi

log()  { echo "$(date '+%Y-%m-%d %H:%M:%S') ℹ  $*"; }
ok()   { echo "$(date '+%Y-%m-%d %H:%M:%S') ✅ $*"; }
warn() { echo "$(date '+%Y-%m-%d %H:%M:%S') ⚠️  $*"; }
err()  { echo "$(date '+%Y-%m-%d %H:%M:%S') ❌ $*" >&2; exit 1; }

# ── Verificar red externa ─────────────────────────────────────────────────────
if ! docker network ls --format '{{.Name}}' | grep -qx web-proxy; then
    warn "Red 'web-proxy' no existe — creándola..."
    docker network create web-proxy
fi

cd "$PROJECT_DIR"

# ── Opción --reset ─────────────────────────────────────────────────────────────
if [[ "${1:-}" == "--reset" ]]; then
    warn "Flag --reset detectado — se borrarán los volúmenes de la BD."
    read -rp "¿Estás seguro? Esta acción es destructiva. [s/N]: " confirm
    [[ "${confirm,,}" == "s" ]] || err "Operación cancelada."
    log "Deteniendo contenedores y borrando volúmenes..."
    docker compose -f docker-compose.yml -f docker-compose.dev.yml down -v
fi

# ── Build y arranque dev ──────────────────────────────────────────────────────
log "Iniciando stack en modo DEV (docker-compose.dev.yml)..."
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build

# ── Esperar Odoo (máx. 90s) ──────────────────────────────────────────────────
log "Esperando que Odoo esté disponible (máx 90s)..."
OK=0
for i in $(seq 1 18); do
    if curl -sf http://127.0.0.1:8069 > /dev/null 2>&1; then
        OK=1; break
    fi
    echo "   ... ${i}/18 (esperando ${i}x5s)"
    sleep 5
done

echo ""
echo "📋 Contenedores activos:"
docker compose -f docker-compose.yml -f docker-compose.dev.yml ps

echo ""
if [[ "$OK" -eq 1 ]]; then
    ok "Stack DEV disponible en http://catastro.local (o http://127.0.0.1:8069)"
    echo ""
    echo "  Acceso: http://catastro.local"
    echo "  Usuario: admin / admin"
    echo "  Logs:    docker compose logs -f odoo"
else
    warn "Odoo no respondió en 90s — revisa los logs:"
    echo "  docker compose -f docker-compose.yml -f docker-compose.dev.yml logs --tail=50 odoo"
    exit 1
fi
