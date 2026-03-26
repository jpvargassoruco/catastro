# Notas de Deploy — Fase 4 `catastro_avaluo`

**Fecha:** 2026-03-26 | **Commit:** `5eaf254` → `main`

## Pasos realizados

### 1. Git commit & push
```bash
git add addons/catastro_avaluo/
git commit -m "feat(catastro_avaluo): módulo de avalúos catastrales"
git push origin main
# → ce0803f..5eaf254 main -> main
```

### 2. Deploy entorno de desarrollo
```bash
# El script deploy.sh está configurado para /opt/catastro (producción WSL).
# En desarrollo se usa docker-compose.dev.yml directamente:
sudo docker compose -f docker-compose.dev.yml up -d --force-recreate odoo

# Resultado:
# ✔ Container catastro-db-1    Healthy
# ✔ Container catastro-odoo-1  Started
```

## Activar el módulo en Odoo

1. Ir a **Configuración → Activar modo desarrollador**
2. Ir a **Aplicaciones → Actualizar lista de aplicaciones**
3. Buscar `catastro_avaluo` e instalarlo

O via CLI:
```bash
sudo docker exec catastro-odoo-1 \
  odoo -d catastro -u catastro_avaluo --stop-after-init
```

## Notas

- `deploy.sh` usa `COMPOSE_DIR=/opt/catastro` hardcodeado para el entorno WSL de producción.
- En desarrollo se debe usar `-f docker-compose.dev.yml` directamente.
- La red `web-proxy` es externa (Traefik, solo en prod) — no existe en dev.
