# Scripts de Deploy — Catastro → Odoo

Colección de scripts Bash para gestionar el ciclo de vida del stack Docker del proyecto Catastro Municipal.

---

## Requisitos previos

- Docker ≥ 28 y Docker Compose Plugin instalados
- Red Docker externa creada: `docker network create web-proxy`
- Entrada en `/etc/hosts`: `127.0.0.1 catastro.local`
- Archivo `.env` creado desde la plantilla: `cp scripts/deploy/.env.example .env`

---

## Scripts disponibles

### `dev.sh` — Entorno de desarrollo

Levanta el stack en modo desarrollo con hot-reload de addons y los puertos `8069`/`8072` expuestos directamente.

```bash
# Arrancar en modo desarrollo
bash scripts/deploy/dev.sh

# Arrancar borrando la BD (reset completo — pide confirmación)
bash scripts/deploy/dev.sh --reset
```

**Comportamiento**:
- Usa `docker-compose.yml` + `docker-compose.dev.yml` (override)
- Los addons en `./addons/` se montan como volumen — basta reiniciar para ver cambios en Python/XML
- Odoo accesible en `http://catastro.local` y en `http://127.0.0.1:8069`

---

### `prod.sh` — Producción (`sudo`)

Deploy de producción con backup automático de BD antes de cualquier cambio.

```bash
sudo bash scripts/deploy/prod.sh
```

**Flujo**:
1. `git pull origin main` — actualiza el código
2. Backup de la BD en `$BACKUP_DIR` (por defecto `/var/backups/catastro`)
3. `docker pull` de la imagen nueva desde GHCR
4. Recrea solo el contenedor `odoo` sin tocar `db` ni `traefik`
5. Health-check con retry hasta 90s
6. Notificación webhook (opcional si `DEPLOY_WEBHOOK_URL` está configurado en `.env`)

---

### `update_module.sh` — Actualizar módulo en caliente

Actualiza uno o más módulos Odoo sin detener todo el stack.

```bash
# Un módulo
bash scripts/deploy/update_module.sh catastro_avaluo

# Varios módulos a la vez
bash scripts/deploy/update_module.sh catastro_predio catastro_avaluo
```

**Flujo**:
1. Detecta el contenedor `odoo` en curso
2. Lanza `odoo -u <modulos> -d <DB> --stop-after-init` dentro del contenedor
3. Reinicia el contenedor con `docker compose restart odoo`
4. Health-check para confirmar que Odoo volvió a estar disponible

⚠️ **Usar cuando**: se hicieron cambios en los archivos Python de un addon y se necesita aplicarlos sin hacer un deploy completo.

---

## Variables de entorno (`.env`)

Copia la plantilla y ajusta los valores:

```bash
cp scripts/deploy/.env.example .env
```

| Variable | Defecto | Descripción |
|----------|---------|-------------|
| `POSTGRES_DB` | `catastro` | Nombre de la BD de Odoo |
| `POSTGRES_USER` | `odoo` | Usuario de PostgreSQL |
| `POSTGRES_PASSWORD` | `odoo_secret` | Contraseña de PostgreSQL |
| `ODOO_DB` | `catastro` | BD que `update_module.sh` actualiza |
| `ODOO_MASTER_PASSWORD` | `catastro_admin_2026` | Master password del panel Odoo |
| `ODOO_IMAGE` | `ghcr.io/jpvargassoruco/catastro:latest` | Imagen para `prod.sh` |
| `BACKUP_DIR` | `/var/backups/catastro` | Directorio de backups |
| `BACKUP_RETENTION` | `7` | Número de backups a conservar |
| `DEPLOY_WEBHOOK_URL` | *(vacío)* | URL webhook Slack/Teams para notificaciones |

---

## Troubleshooting rápido

```bash
# Ver logs de Odoo en tiempo real
docker compose logs -f odoo

# Entrar al contenedor Odoo
docker compose exec odoo bash

# Reiniciar solo Odoo
docker compose restart odoo

# Ver qué contenedores están corriendo
docker compose ps

# Borrar volúmenes y empezar desde cero (⚠️ DESTRUCTIVO)
docker compose down -v
```
