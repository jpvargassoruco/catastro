#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# update_module.sh — Actualiza uno o más módulos Odoo en caliente
#
# Uso: bash scripts/deploy/update_module.sh <modulo1> [modulo2 ...]
#
# Ejemplo:
#   bash scripts/deploy/update_module.sh catastro_avaluo
#   bash scripts/deploy/update_module.sh catastro_predio catastro_avaluo
#
# Este script lanza `odoo -u <modulo>` dentro del contenedor Odoo ya corriendo,
# usando la base de datos configurada en .env (ODOO_DB) o en odoo.conf.
# ─────────────────────────────────────────────────────────────────────────────
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"
ENV_FILE="$PROJECT_DIR/.env"

# Cargar .env si existe
if [[ -f "$ENV_FILE" ]]; then
    set -o allexport
    source "$ENV_FILE"
    set +o allexport
fi

ODOO_DB="${ODOO_DB:-catastro}"

log()  { echo "$(date '+%Y-%m-%d %H:%M:%S') ℹ  $*"; }
ok()   { echo "$(date '+%Y-%m-%d %H:%M:%S') ✅ $*"; }
err()  { echo "$(date '+%Y-%m-%d %H:%M:%S') ❌ $*" >&2; exit 1; }

# ── Validar argumentos ────────────────────────────────────────────────────────
[[ $# -ge 1 ]] || err "Especifica al menos un módulo. Uso: $0 <modulo1> [modulo2 ...]"

MODULES="$*"
MODULES_CSV="${MODULES// /,}"   # "modA modB" → "modA,modB"

log "Módulos a actualizar: $MODULES_CSV"
log "Base de datos: $ODOO_DB"

# ── Encontrar el contenedor Odoo ──────────────────────────────────────────────
cd "$PROJECT_DIR"
ODOO_CONTAINER="$(docker compose ps -q odoo 2>/dev/null || true)"
[[ -n "$ODOO_CONTAINER" ]] || err "Contenedor 'odoo' no está corriendo. Arráncalo primero."

log "Contenedor Odoo: $ODOO_CONTAINER"

# ── Detener el servicio Odoo dentro del contenedor ────────────────────────────
log "Deteniendo proceso Odoo en el contenedor..."
docker exec "$ODOO_CONTAINER" kill -SIGTERM 1 2>/dev/null || true
sleep 2

# ── Lanzar actualización ──────────────────────────────────────────────────────
log "Ejecutando: odoo -u $MODULES_CSV -d $ODOO_DB --stop-after-init"
docker exec "$ODOO_CONTAINER" \
    odoo \
    -u "$MODULES_CSV" \
    -d "$ODOO_DB" \
    --stop-after-init \
    2>&1

# ── Reiniciar el contenedor para volver al modo normal ────────────────────────
log "Reiniciando contenedor Odoo para volver al modo server..."
docker compose restart odoo

# ── Esperar health-check ──────────────────────────────────────────────────────
log "Esperando que Odoo vuelva a estar disponible (máx 60s)..."
OK=0
for i in $(seq 1 12); do
    if curl -sf http://127.0.0.1:8069 > /dev/null 2>&1 || \
       curl -sf http://127.0.0.1 > /dev/null 2>&1; then
        OK=1; break
    fi
    echo "   ... ${i}/12 (${i}x5s)"
    sleep 5
done

echo ""
if [[ "$OK" -eq 1 ]]; then
    ok "Módulo(s) '$MODULES_CSV' actualizados y Odoo disponible."
else
    echo "⚠️  Odoo no respondió en 60s tras la actualización — revisa los logs:"
    echo "  docker compose logs --tail=50 odoo"
    exit 1
fi
