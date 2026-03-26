# Catastro → Odoo 19: Fase 2 & Fase 3 Implementation Plan

Migrar el SIICAT legacy (PHP/PostgreSQL) a módulos Odoo 19 custom. Este plan cubre **Fase 2** (Configuración Inicial) y **Fase 3** (Módulo `catastro_predio`).

## User Review Required

> [!IMPORTANT]
> **Versión Odoo → DECIDIDO: Odoo 17.0 LTS** ✅
> Bajamos de 19.0 a 17.0 LTS por: ecosistema OCA maduro, `base_geoengine` disponible (Fase 6/GIS), `l10n_bo` estable, y skills que cubren v17 exactamente. `docker-compose.yml` y `__manifest__.py` actualizados.

> [!WARNING]
> **PostGIS / GIS**: El módulo OCA `base_geoengine` v17 está disponible. Aplica a Fase 6 (Cartografía), no a estas fases.

> [!IMPORTANT]
> **Plan de cuentas → DECIDIDO**: Instalar `l10n_bo` como base y extender con un plan de cuentas municipal personalizado (IPBI/impuesto predial, tasas catastrales, derechos de empadronamiento). Esto va en Fase 2.

---

## Proposed Changes

### Fase 2 — Configuración Inicial Odoo

Pasos manuales/asistidos (sin código custom):

1. **Crear base de datos** via `catastro.local/web/database/manager` con nombre `catastro_db`
2. **Instalar módulos nativos**:
   - `contacts` — Base de contribuyentes (`res.partner`)
   - `account` — Contabilidad y cuentas por cobrar
   - `fleet` — Vehículos
   - `documents` — Gestión documental
   - `l10n_bo` — Localización Bolivia (plan de cuentas)
3. **Configurar compañía**: Gobierno Autónomo Municipal de Vallegrande
4. **Configurar usuarios/roles** equivalentes a SIICAT (admin, operador, consultor)

> No se requiere código custom para Fase 2. Es configuración pura vía la UI de Odoo.

---

### Fase 3 — Módulo `catastro_predio`

#### [NEW] Estructura del módulo

```
addons/catastro_predio/
├── __init__.py
├── __manifest__.py
├── models/
│   ├── __init__.py
│   ├── catastro_predio.py          # Modelo principal
│   ├── catastro_colindante.py      # Línea de colindantes
│   ├── catastro_tradicion.py       # Historial de dominio
│   └── catastro_edificacion.py     # Construcciones en el predio
├── views/
│   ├── catastro_predio_views.xml   # Form, Tree, Search
│   ├── catastro_menu.xml           # Menús del módulo
│   └── catastro_edificacion_views.xml
├── security/
│   ├── ir.model.access.csv         # ACLs
│   └── catastro_security.xml       # Grupos y record rules
├── data/
│   └── catastro_data.xml           # Datos iniciales (zonas, uso de suelo)
└── demo/
    └── catastro_demo.xml           # Datos demo (opcional)
```

---

#### [NEW] [__manifest__.py](file:///home/kali/catastro/addons/catastro_predio/__manifest__.py)

Metadatos del módulo: nombre, versión, dependencias (`base`, `contacts`, `mail`), datos, seguridad.

#### [NEW] [catastro_predio.py](file:///home/kali/catastro/addons/catastro_predio/models/catastro_predio.py)

Modelo `catastro.predio` con campos:

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `name` | Char | Nombre/descripción del predio |
| `clave_catastral` | Char | Código catastral único (ej: `VG-U-001-002`) |
| `tipo` | Selection | `urbano` / `rural` |
| `propietario_id` | Many2one → `res.partner` | Propietario actual |
| `zona` | Char | Zona catastral |
| `sector` | Char | Sector |
| `manzano` | Char | Manzano (urbano) |
| `lote` | Char | Número de lote |
| `superficie_terreno` | Float | m² terreno |
| `superficie_construida` | Float | m² construcción |
| `uso_suelo` | Selection | Residencial/Comercial/Industrial/Agrícola |
| `valor_terreno` | Monetary | Valor catastral terreno |
| `valor_construccion` | Monetary | Valor catastral construcción |
| `valor_total` | Monetary | Computed: terreno + construcción |
| `colindante_ids` | One2many | Colindantes (norte, sur, este, oeste) |
| `tradicion_ids` | One2many | Historial de propietarios |
| `edificacion_ids` | One2many | Construcciones |
| `state` | Selection | `borrador`, `registrado`, `inactivo` |

Hereda `mail.thread` y `mail.activity.mixin` para chatter/auditoría.

#### [NEW] [catastro_colindante.py](file:///home/kali/catastro/addons/catastro_predio/models/catastro_colindante.py)

Modelo `catastro.colindante`: `predio_id`, `orientacion` (N/S/E/O), `descripcion`, `medida`.

#### [NEW] [catastro_tradicion.py](file:///home/kali/catastro/addons/catastro_predio/models/catastro_tradicion.py)

Modelo `catastro.tradicion`: `predio_id`, `propietario_id`, `fecha_inicio`, `fecha_fin`, `tipo_operacion` (compra/donación/herencia), `notas`.

#### [NEW] [catastro_edificacion.py](file:///home/kali/catastro/addons/catastro_predio/models/catastro_edificacion.py)

Modelo `catastro.edificacion`: `predio_id`, `tipo_construccion`, `superficie`, `niveles`, `material`, `estado_conservacion`, `valor`.

#### [NEW] [catastro_predio_views.xml](file:///home/kali/catastro/addons/catastro_predio/views/catastro_predio_views.xml)

- **Form view**: Tabs para datos generales, colindantes, tradición, edificaciones
- **Tree view**: Lista con clave catastral, propietario, zona, tipo, valor
- **Search view**: Filtros por tipo, zona, propietario; agrupación por zona y uso de suelo

#### [NEW] [catastro_security.xml](file:///home/kali/catastro/addons/catastro_predio/security/catastro_security.xml)

Grupos: `catastro_consultor` (lectura), `catastro_operador` (CRUD), `catastro_admin` (todo).

#### [NEW] [ir.model.access.csv](file:///home/kali/catastro/addons/catastro_predio/security/ir.model.access.csv)

ACLs para cada modelo × grupo.

---

## Verification Plan

### Automated Tests

```bash
# Dentro del contenedor Odoo
odoo -d catastro_db -i catastro_predio --test-enable --stop-after-init --log-level=test
```

### Manual Verification

1. Instalar `catastro_predio` desde Aplicaciones en Odoo
2. Crear un predio de prueba (urbano y rural)
3. Agregar colindantes, edificaciones, tradición
4. Verificar que el chatter registra cambios
5. Probar filtros de búsqueda
6. Verificar permisos por grupo (consultor vs operador vs admin)
