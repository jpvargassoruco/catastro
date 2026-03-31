# Wiki del Proyecto Catastro → Odoo

## Resumen del Proyecto

Este proyecto consiste en **migrar el sistema de catastro municipal SIICAT** (escrito en PHP + PostgreSQL) hacia una aplicación basada en **Odoo 19**, reutilizando las funcionalidades nativas de la plataforma y construyendo módulos personalizados solo donde sea necesario.

- **Sistema de origen**: SIICAT (PHP, ~298 archivos, PostgreSQL, Apache/Windows)
- **Sistema de destino**: Odoo 19.0 (Python, módulos nativos + addons custom)
- **Municipio**: Vallegrande, Bolivia
- **DB origen**: PostgreSQL en `192.168.0.150`, base de datos `vallegrande`, usuario `postgres`

---

## Módulos del Sistema Legacy (SIICAT)

### 1. Predios (Bienes Inmuebles)
- `siicat_form_predio.php` (68KB) — formulario principal de predios
- `siicat_anadir_datos.php` (89KB) — ingreso de datos prediales
- `siicat_ver_datos.php`, `siicat_ver_datos_rural.php` — visualización
- `siicat_modificar_codigo.php` (54KB) — modificación de datos
- `siicat_check_datos.php` (44KB) — validaciones
- `siicat_lista_datos.php`, `siicat_lista_datos_rural.php` — listados
- `siicat_busqueda.php`, `siicat_busqueda_resultado.php` — búsqueda avanzada
- Soporta predios **urbanos** y **rurales**

### 2. Contribuyentes / Propietarios
- `siicat_form_contrib.php` (24KB) — ficha de contribuyente
- `siicat_contrib_bienes.php` — bienes del contribuyente
- `siicat_contrib_foto.php` — fotografías
- `siicat_lista_contribuyentes.php` — listado
- `siicat_tradicion.php` (30KB) — tradición de dominio / historial

### 3. Impuestos y Colecturía
- `siicat_impuestos.php` (60KB) — gestión principal de impuestos
- `siicat_impuestos_calc.php` (90KB) — cálculo de impuestos
- `siicat_impuestos_boleta_de_pago.php` (55KB) — boletas de pago
- `siicat_impuestos_tablas.php` (99KB) — tablas de tasas e impuestos
- `siicat_impuestos_ajustes.php` (33KB) — ajustes y correcciones
- `siicat_impuestos_cotizaciones.php` (38KB) — cotizaciones
- `siicat_impuestos_transferencia.php` — impuesto por transferencia
- Reportes: ingresos recibidos, montos adeudados, boletas impresas
- Cálculo de impuestos por cuotas semestrales
- Liquidación de impuestos en transferencias de dominio

### 4. Transferencias de Dominio
- `siicat_transferencia.php` (28KB) — proceso de transferencia
- `siicat_transfer.php` (14KB) — gestión de transferencias
- `siicat_check_transferencia.php` (15KB) — validaciones
- `siicat_impuestos_generar_preliquid_transfer*.php` — preliquidaciones

### 5. Planos Catastrales (Cartografía)
- `siicat_plano_catastral_generar.php` (31KB) — generación de planos
- `siicat_generar_mapfile.php` (25KB) — generación de MapServer mapfiles
- `siicat_generar_mapfile_predio.php` — plano por predio
- `siicat_generar_mapfile_rural.php` — plano rural
- `siicat_generar_mapfile_planocatastral.php` — plano catastral completo
- `siicat_generar_dxffile.php` (11KB) — exportación DXF (AutoCAD)
- `siicat_generar_htmlfile_zoom.php` — visualización web con zoom
- Tecnología: **MapServer** + geometría PostGIS (SRID=-1, sistema local)
- Tipos: polígonos de predios, líneas divisorias, vértices, ochaves
- Certificados catastrales con plano incluido

### 6. Certificados y Documentos Oficiales
- `siicat_certificado_catastral_generar.php` (48KB) — certificado catastral
- `siicat_informe_empadronamiento_generar.php` (40KB) — informe de empadronamiento
- `siicat_informe_tecnico_generar.php` — informe técnico
- `siicat_linea_nivel_generar.php` (39KB) — línea de nivel / cota
- `siicat_linea_nivel_generar_alternativo.php` — versión alternativa
- `siicat_planos_leer_datos.php` — datos para planos
- Generados con **FPDF** (`siicat_fpdf.php`, 45KB)

### 7. Patentes Comerciales
- `siicat_form_patente.php` (19KB) — formulario de patente
- `siicat_patentes_resultado.php` (18KB) — resultados
- `siicat_patentes_rubros.php` (24KB) — rubros comerciales
- `siicat_patentes_tablas.php` (19KB) — tablas de tasas
- `siicat_patentes_licencia_generar.php` — generación de licencias
- `siicat_check_patente.php` — validaciones

### 8. Vehículos
- `siicat_form_vehic.php` (19KB) — ficha de vehículo
- `siicat_vehic_resultado.php`, `siicat_vehic_listado.php` — vistas
- `siicat_vehic_estadisticas.php` — estadísticas
- `siicat_check_vehic.php` — validaciones

### 9. Edificaciones
- `siicat_form_edif.php` (24KB) — ficha de edificación
- `siicat_ver_edif.php` (25KB) — visualización
- `siicat_check_edif.php` — validaciones
- `edificacion_valor.php` — cálculo de valores de edificación

### 10. Documentos y Adjuntos
- `docs_gestion.php`, `docs_gestion_tab.php` — gestión de documentos
- `docs_config.php` — configuración del módulo de documentos
- `docs_descargar.php` — descarga de documentos
- `siicat_upload_documento.php`, `siicat_upload_foto.php`

### 11. Sistema / Administración
- `siicat_usuarios.php` (29KB) — gestión de usuarios y roles
- `siicat_herramientas.php` (27KB) — herramientas del sistema
- `siicat_backup.php` (20KB) — backup de base de datos
- `siicat_sistema.php` (10KB) — configuración del sistema
- `siicat_cambios.php` (20KB) — auditoría de cambios
- `siicat_registro.php` (17KB) — registro de actividad

### 12. Gravámenes e Hipotecas
- `siicat_gravamen.php` (13KB) — gestión de gravámenes sobre predios

### 13. Datos Socioeconómicos
- `siicat_datos_socioeco.php` (16KB) — datos socioeconómicos del predio

---

## Arquitectura de Destino (Odoo 19)

### Módulos Nativos de Odoo a Reutilizar

| Módulo SIICAT | Módulo Odoo Nativo | Notas |
|---------------|-------------------|-------|
| Contribuyentes | `res.partner` | Ya incluye contactos, fotos, direcciones |
| Impuestos | `account` (Contabilidad) | Facturas, pagos, cuentas por cobrar |
| Boletas de pago | `account.payment`, `account.move` | Flujo de cobranza nativo |
| Documentos | `documents` | Módulo de gestión documental |
| Usuarios | `res.users`, `res.groups` | Sistema de roles y permisos |
| Auditoría | `mail.thread` (chatter) | Seguimiento de cambios en registros |
| Backup | Admin → Database Manager | Panel de Odoo |
| Vehículos | `fleet` | Módulo Fleet de Odoo |
| Patentes/Licencias | `sale` o `helpdesk` (v19) | A evaluar |

### Módulos Custom (`addons/`)

| Módulo | Nombre Técnico | Estado | Prioridad |
|--------|---------------|--------|-----------|
| Predios catastrales | `catastro_predio` | ✅ Implementado | Alta |
| Avalúos catastrales | `catastro_avaluo` | ✅ Implementado | Alta |
| Planos + Cartografía | `catastro_mapa` | ✅ Implementado | Media |
| Certificados PDF | `catastro_certificados` | ✅ Implementado | Alta |
| Impuestos prediales | `catastro_impuestos` | ✅ Implementado | Alta |
| Transferencias de dominio | `catastro_transferencia` | ✅ Implementado | Alta |
| Línea de nivel / informes | `catastro_informes` | ✅ Implementado | Media |
| Gravámenes | `catastro_gravamen` | ✅ Implementado | Media |

---

## Infraestructura

### Entorno de Desarrollo

- **Host**: Windows 11 con WSL2 (distro: `catastro`) o Linux
- **Local Host URL**: `http://catastro.local`
- **Compose ubicado en**: Directorio raíz
- **Red proxy**: `web-proxy` (Docker network externa)

### Stack Docker

```
┌─────────────────────────────────────────┐
│  Linux / WSL Host                        │
│  ┌────────────────────────────────────┐  │
│  │  Pila de Docker Catastro           │  │
│  │  ┌──────────────────────────────┐  │  │
│  │  │  Traefik :80/:8080           │  │  │
│  │  │  (traefik:latest)            │  │  │
│  │  ├──────────────────────────────┤  │  │
│  │  │  Odoo :8069/:8072            │  │  │
│  │  │  (odoo:17.0 LTS)             │  │  │
│  │  ├──────────────────────────────┤  │  │
│  │  │  PostgreSQL :5432            │  │  │
│  │  │  (postgres:16)               │  │  │
│  │  └──────────────────────────────┘  │  │
│  └────────────────────────────────────┘  │
└─────────────────────────────────────────┘
```

### Versiones

| Servicio | Imagen | Versión |
|----------|--------|---------|
| Odoo | `ghcr.io/pothoko/catastro_01` | 17.0 LTS custom |
| PostgreSQL | `postgres:16` | 16.x |
| Traefik | `traefik:latest` | v3.x |

### Archivos de Configuración

```
/home/fisbert/.../catastro_01/ ← repositorio git
├── README.md
├── docker-compose.yml         
├── config/
│   └── odoo.conf              ← configuración de Odoo
├── addons/
│   ├── catastro_predio/       ← ✅ Módulo predios catastrales
│   ├── catastro_avaluo/       ← ✅ Módulo avalúos catastrales
│   ├── catastro_impuestos/    ← ✅ Motor tributario y facturación CAJA
│   ├── catastro_mapa/         ← ✅ Renderizador Geoespacial OWL Map
│   ├── catastro_certificados/ ← ✅ Generador de Folios en QWeb
│   ├── catastro_informes/     ← ✅ Generador Empadronamientos QWeb
│   ├── catastro_transferencia/← ✅ Aprobaciones legales y tracking
│   └── catastro_gravamen/     ← ✅ Restricciones judiciales 
├── docs/
│   ├── wiki.md                ← Documentación Master
│   └── informe_tecnico...md   ← Informe Despliegue Oficial
├── scripts/                   
│   └── migrate_siicat_to_odoo.py ← ETL Migración de datos 
└── siicat/                    ← código PHP legacy (obsoleto/referencia)
```

---

## Arquitectura Interna y Justificación de Scaffolding

Durante la ejecución del Plan de Migración y modularidad de Odoo 17, se aplicaron los siguientes patrones de diseño basados en la separación de responsabilidades (*Separation of Concerns*):

### ¿Por qué separar `catastro_impuestos` de `catastro_predio`?
**Racionalidad:** La gestión territorial (Predios) debe ser independiente a la fluctuación contable y financiera anual de las normativas de Alcaldía (Impuestos). Agrupar ambos forzaría actualizaciones al modelo técnico del predio cada que cambie la ley fiscal.
**Datos Técnicos:**
- **Asociación:** Modelo `catastro.impuesto` (relación `Many2one` hacia `catastro_predio`).
- **Integración:** Dependencia de `account` base para extender `account.move` en el futuro para registrar deudas en el balance por cobrar del municipio (Caja General).

### ¿Por qué `catastro_transferencia` es un módulo diferente?
**Racionalidad:** Una transferencia de dominio es un flujo de trabajo legal burocrático, no solo una modificación de metadato. Requiere aprobaciones de diferentes departamentos (Avalúos, Legal, Recaudadora), hereda de estados lógicos (Borrador -> Revisión -> Liquidado -> Aprobado) y hace uso intensivo de Auditoría (`mail.thread`) para rastrear quién autorizó el cambio de dueño (Tradición de Dominio).
**Datos Técnicos:**
- **Dependencias:** Necesita a `catastro_predio` (dónde aplicar el cambio) e `impuestos` (para calcular el IMT, Impuesto Municipal a las Transferencias).
- **Herencia:** Utiliza `mail.thread` y `mail.activity.mixin` de Odoo para el rastro y discusión entre funcionarios municipales.

### ¿Por qué encapsular `catastro_gravamen`?
**Racionalidad:** Los gravámenes cambian el estatus legal restrictivo de un predio. Mientras un predio esté sometido a una carga judicial o hipotecaria activa, sus transferencias habitualmente quedan inhabilitadas. Es una capa de seguridad jurídica pura, manteniéndose desacoplada; conectando acreedores o jueces al modelo base nativo de roles de Odoo `res.partner`.
**Datos Técnicos:**
- **Asociación:** Modelo autónomo y centralizado con dependencias directas en `catastro_predio`.

### Módulo de Motor Qweb PDF: `catastro_certificados`
**Racionalidad:** En SIICAT (Legacy PHP), utilizar librerías manuales como FPDF requiere crear páginas calculando coordenadas `X,Y` manualmente (`siicat_certificado_catastral_generar.php`). Este módulo aísla y transiciona todos los reportes hacia QWeb Web Engine. Permite diseñar estructuralmente usando HTML semántico (Bootstrap) y emitir reportes de PDF limpios y unificados, al mismo tiempo que mantiene una base de datos propia (`catastro.certificado`) para trazar visualmente cuándo y a quién se entregó un folio impreso.

### ¿Por qué diferenciar `catastro_informes` de `catastro_certificados`?
**Racionalidad:** Un "Certificado" es funcionalmente estático exportado inmediatamente por el contribuyente. Un "Informe Técnico" (Líneas de Cota, Reporte de Empadronamiento) necesita el levantamiento de información humana. Esta distinción promueve el modelo en base al uso de "Workflows Dinámicos", requiriendo asignación interna (`res.users` Técnicos) y un campo de recolección de variables físicas o conclusiones, algo impropio en el módulo de Certificados.

### Módulo de Arquitectura Cartográfica: `catastro_mapa`
**Racionalidad:** Las librerías de renderizado de MapServer Legacy corrían aisladas en el SO. Este módulo base asimila las capas espaciales (como atributos JSON o extensiones de Base de Datos Geoespaciales PostGIS). Para evitar MapServer, instanciamos componentes OWL Javascript nativos que renderizan los vértices GeoJSON dinámicamente usando mapas OpenStreetMap/Leaflet base en las vistas form de Odoo.

---

## 🚀 Guía Práctica de Sistema, Despliegue y Uso (Operación)

### 1. ¿Cómo funciona la nueva arquitectura tecnológica?
El sistema Catastral ahora abandonó el código *espagueti* disperso y se transformó en un ecosistema robusto de **Docker Containers**. Se nutre de tres piezas autónomas:
1. **Odoo 17 (Backend ERP):** Software central que procesa Python (para los cálculos de impuestos), vistas XML (Interfaces) y QWeb/OWL (para PDF y Mapas).
2. **PostgreSQL 16 (Base de Datos DWH):** Reemplaza a la antigua BD plana de SIICAT, conteniendo ahora tablas dinámicas unificadas con la NIIF / Contabilidad Odoo.
3. **Traefik (Enrutador de Tráfico Inteligente):** Intercepta lo que escribes en la PC y te conecta directamente, evitando puertos confusos (`http://catastro.local`).

### 2. Comandos Operativos y Puesta en Marcha Inicial
Si necesitas iniciar, reiniciar o reconstruir todo el código, debes estar en tu carpeta raíz (`.../catastro_01`) y ejecutar:
```bash
sudo docker compose up -d --build
```
> *(El flag `--build` fuerza a Odoo a reconocer y actualizar cualquier archivo o vista que hayas modificado o añadido hoy).*

Si quieres revisar el "corazón" del sistema mientras arranca:
```bash
sudo docker compose logs -f odoo
```

### 3. Accesos y Credenciales Creados
Para ingresar físicamente a la interfaz moderna:
- **Enlace de Operación Global:** [http://catastro.local](http://catastro.local)
- **Credenciales para acceder una vez la BD viva:**
  - **Correo Empleado:** `admin` *(o tu propio correo ingresado previamente)*
  - **Clave Secreta:** `admin` *(o la generada al construir la instalación)*
- **Master Password (Seguridad de Base de Datos para Odoo):** `catastro_admin_2026` (Útiles para borrar o clonar entornos de prueba).

### 4. Entorno dentro de Odoo (Instalación Catastral)
Dado que Odoo arrancó *virgen*, debes indicarle qué módulos de alcaldía requiere:
1. Abre Odoo, ve a la botonera principal e ingresa a **Aplicaciones**.
2. En la barra de búsqueda de extremo derecho, **Cierra la X en el filtro de 'Aplicaciones'**. Necesitamos buscar por "Módulos Libres".
3. Busca la palabra **`catastro`**.
4. ¡Boom! Aparecerán nuestros 6 pilares de reingeniería (`catastro_predio`, `catastro_impuestos`, etc). Haz clic en **"Activar"** prioritariamente en *(Catastro - Predio y Base Territorial)* primero, y luego los demás.

### 5. Resumen de Flujo de Trabajo Funcional (El Producto Final)
Tu municipio ahora cuenta con:
- 🗺️ **Cartografía en Vuelo:** Pegando coordenadas (GeoJSON), el sistema dibuja dinámicamente tu predio con algoritmos satelitales (`Leaflet` embebido en OWL).
- 💰 **Colecturía Nativas e Integrada:** La Fase 5 mató los cálculos SQL obsoletos. Al oprimir *"Pagar en Caja"*, se dispara un `account.move` legalmente vinculante con el libro mayor interno y un recibo hermoso.
- 📜 **Oficina Virtual sin Papel Impreso Externo:** Los peritos y secretarios entran a exportar Certificados Catastrales y Líneas de Cota sin programar coordenadas (Todo automatizado en QWeb y PDF Responsive).
- 🤖 **Herramientas de Migración:** El archivo ETL en `scripts/migrate_siicat_to_odoo.py` puede usarse cuantas veces plazca para chupar las tablas de `vallegrande` o `paria` e inyectarlas a la vida en Odoo, mapeando *Contribuyentes a Contactos Oficiales* por XML-RPC.

### 6. Inyección de Datos Demo de Paria y Migración Masiva

#### A) Inyectar los Datos Demo Nativos al Código Fuente (Seguro para GitHub)
Ya hemos preparado una batería limpia de `contactos_importar.csv` en la carpeta `addons/catastro_predio/demo/`. Al instalar o actualizar el módulo `catastro_predio` marcando la casilla de "Cargar Datos de Demostración" durante la creación de la Base de Datos Odoo, estos registros se cargarán de forma nativa sin tumbar el servidor.

#### B) Volcar el Backup Completo (`paria.backup`) dentro de Docker
Para migrar el resto (las miles de filas) sin acoplarlos al repositorio, primero restaura el backup invisiblemente dentro del contenedor PostgreSQL de Odoo:

```bash
# Entrar a la carpeta raíz del proyecto
cd catastro_01

# 1. Crear una base de datos temporal vacía llamada 'paria' dentro del Contenedor DB
sudo docker exec -i catastro_01-db-1 createdb -U odoo -O odoo paria

# 2. Restaurar tu archivo físico 'paria.backup' directo al contenedor
sudo docker exec -i catastro_01-db-1 pg_restore -U odoo -d paria < ../paria.backup
```

#### C) Disparar el Script ETL para que Odoo lo absorba (XML-RPC)
Una vez que `paria_db` está viva dentro de Docker, usamos el contenedor de Odoo (que ya tiene `psycopg2` y acceso de red interno a la base temporal) para inyectar todo a Odoo:

```bash
# Encender motor script Python de Migración (dentro del contenedor odoo)
sudo docker exec -it catastro_01-odoo-1 python3 /mnt/extra-addons/../scripts/migrate_siicat_to_odoo.py
```
