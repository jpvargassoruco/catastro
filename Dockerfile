# ─────────────────────────────────────────────────────────────────────────────
# Imagen personalizada Odoo 17.0 LTS — Catastro Municipal Vallegrande
# ─────────────────────────────────────────────────────────────────────────────
# Base oficial Odoo 17.0 (Debian Bookworm)
FROM odoo:17.0

# Metadatos OCI
LABEL org.opencontainers.image.source="https://github.com/Pothoko/catastro_01" \
      org.opencontainers.image.description="Odoo 17 LTS - Catastro Municipal Vallegrande" \
      org.opencontainers.image.licenses="LGPL-3.0"

# Cambiar a root para instalar dependencias del sistema
USER root

# ── Dependencias del sistema ──────────────────────────────────────────────────
# NOTA: GDAL/GIS se agrega en Fase 6 (base_geoengine). Por ahora solo utils.
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    git \
    && rm -rf /var/lib/apt/lists/*

# ── Dependencias Python ───────────────────────────────────────────────────────
# Instalar paquetes Python adicionales con pip (como usuario odoo)
COPY requirements.txt /tmp/catastro-requirements.txt
RUN pip3 install --no-cache-dir -r /tmp/catastro-requirements.txt

# ── Addons custom del proyecto ────────────────────────────────────────────────
# Los addons se copian a /mnt/extra-addons dentro de la imagen.
# En desarrollo local se montan como volumen (override en docker-compose).
COPY addons/ /mnt/extra-addons/

# Aseguramos permisos correctos
RUN chown -R odoo:odoo /mnt/extra-addons

# Volver al usuario odoo (no correr como root en producción)
USER odoo

# El entrypoint oficial de la imagen base maneja el CMD
