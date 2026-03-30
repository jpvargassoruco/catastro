#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# deploy.sh — Script de actualización Odoo Catastro en WSL
# Uso: sudo bash /opt/catastro/scripts/deploy.sh
# ─────────────────────────────────────────────────────────────────────────────
set -euo pipefail

COMPOSE_DIR="/opt/catastro"
IMAGE="ghcr.io/Pothoko/catastro_01:latest"

log() { echo "$(date '+%Y-%m-%d %H:%M:%S') $*"; }

log "🔄 Iniciando deploy de Catastro..."
cd "$COMPOSE_DIR"

# 1. Actualizar código desde git
log "📥 Actualizando código fuente..."
git pull origin main

# 2. Pull de la última imagen desde GHCR
log "📦 Descargando imagen $IMAGE ..."
docker pull "$IMAGE"

# 3. Intentar recrear solo Odoo (sin bajar DB ni Traefik)
log "🚀 Recreando contenedor Odoo..."
docker compose up -d --no-deps --force-recreate odoo

# 4. Esperar 10s y verificar si hay error de red
sleep 10
if docker compose logs --tail=20 odoo 2>&1 | grep -q "could not translate host name\|name resolution"; then
    log "⚠️  Error de red detectado — realizando restart completo..."
    docker compose down
    docker compose up -d
    sleep 15
fi

# 5. Verificar salud (máx 90s)
log "⏳ Esperando que Odoo esté disponible (máx 90s)..."
OK=0
for i in $(seq 1 18); do
  if curl -sf http://127.0.0.1 > /dev/null 2>&1; then
    OK=1
    break
  fi
  echo "   ... esperando ($((i*5))s)"
  sleep 5
done

echo ""
echo "📋 Contenedores activos:"
docker compose ps

echo ""
if [ "$OK" -eq 1 ]; then
  log "✅ Deploy completado — Odoo disponible en http://catastro.local"
else
  log "⚠️  Deploy completado pero Odoo no respondió en 90s — revisar logs:"
  log "   sudo docker compose -f $COMPOSE_DIR/docker-compose.yml logs --tail=50 odoo"
  exit 1
fi
