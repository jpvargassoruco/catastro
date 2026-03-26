# Módulo `catastro_avaluo` — Especificación Técnica

**Fecha:** 2026-03-26  
**Odoo:** 17 Community LTS  
**Depende de:** `catastro_predio`

---

## Modelos

### `catastro.avaluo`

Avalúo catastral de un predio para una gestión fiscal.

| Campo | Tipo | Descripción |
|---|---|---|
| `name` | Char | Secuencia automática `AVL/YYYY/NNNN` |
| `predio_id` | Many2one → `catastro.predio` | Predio evaluado (required) |
| `gestion` | Integer | Año fiscal |
| `tabla_valor_id` | Many2one → `catastro.tabla.valor` | Tabla de referencia |
| `superficie_terreno` | Float | m² de terreno |
| `superficie_construida` | Float | m² construidos |
| `valor_unitario_terreno` | Float | Bs/m² de terreno |
| `valor_unitario_construccion` | Float | Bs/m² de construcción |
| `valor_terreno` | Float | Computed: sup × VU |
| `valor_construccion` | Float | Computed: sup × VU |
| `valor_catastral` | Float | Computed: terreno + construcción |
| `fecha_calculo` | Date | Fecha del cálculo |
| `state` | Selection | `borrador → calculado → aprobado → vigente → historico` |

**Constraint:** `UNIQUE(predio_id, gestion)` — un solo avalúo vigente por predio/gestión.

**Flujo de estados:**
```
borrador → [Calcular] → calculado → [Aprobar] → aprobado → [Publicar] → vigente → [Archivar] → historico
```

---

### `catastro.tabla.valor`

Tabla de valores unitarios por zona, uso de suelo y gestión.

| Campo | Tipo | Descripción |
|---|---|---|
| `name` | Char | Computed: `Zona X / Uso YYYY` |
| `zona` | Char | Zona catastral |
| `uso_suelo` | Selection | `residencial / comercial / industrial / mixto` |
| `tipo_predio` | Selection | `urbano / rural` |
| `gestion` | Integer | Año fiscal |
| `valor_terreno` | Float | Bs/m² terreno |
| `valor_construccion` | Float | Bs/m² construcción |
| `active` | Boolean | Archivado nativo Odoo |

**Constraint:** `UNIQUE(zona, uso_suelo, tipo_predio, gestion)`

---

### Extensión de `catastro.predio`

Campos y métodos agregados por herencia delegada (`_inherit`):

| Campo | Tipo | Descripción |
|---|---|---|
| `avaluo_ids` | One2many → `catastro.avaluo` | Todos los avalúos |
| `avaluo_vigente_id` | Many2one | Pointer al avalúo en estado `vigente` |
| `valor_catastral` | Float related | Valor del avalúo vigente |
| `avaluo_count` | Integer | Contador para smart button |

---

### Wizard `catastro.wizard.recalculo.masivo`

Recálculo masivo de avalúos con filtros.

| Campo | Descripción |
|---|---|
| `zona` | Filtrar por zona |
| `tipo_predio` | Filtrar por tipo (urbano/rural/todos) |
| `gestion` | Gestión a generar |
| `tabla_valor_id` | Tabla de referencia a aplicar |
| `solo_sin_avaluo` | Solo predios sin avalúo existente |

**Lógica:** Por cada predio del dominio filtrado:
- Si existe avalúo en estado `calculado` → `write()` (actualiza valores)
- Si no existe → `create()` con `predio_id` nuevo

---

## Archivos del módulo

```
catastro_avaluo/
├── __manifest__.py
├── __init__.py
├── models/
│   ├── catastro_avaluo.py       # Modelo principal
│   ├── catastro_tabla_valor.py  # Tabla de valores unitarios
│   └── catastro_predio.py       # Herencia de predio
├── wizard/
│   ├── wizard_recalculo_masivo.py
│   └── wizard_recalculo_masivo_views.xml
├── views/
│   ├── catastro_avaluo_views.xml
│   ├── catastro_tabla_valor_views.xml
│   ├── catastro_predio_avaluo_views.xml
│   └── menu_views.xml
├── data/
│   └── ir_sequence_data.xml     # Secuencia AVL/YYYY/NNNN
├── demo/
│   └── demo_tabla_valor.xml
└── security/
    └── ir.model.access.csv
```

---

## Seguridad

| Modelo | Grupo | CRUD |
|---|---|---|
| `catastro.avaluo` | `catastro_predio.group_catastro_user` | R |
| `catastro.avaluo` | `catastro_predio.group_catastro_manager` | CRUD |
| `catastro.tabla.valor` | `catastro_predio.group_catastro_user` | R |
| `catastro.tabla.valor` | `catastro_predio.group_catastro_manager` | CRUD |
| `catastro.wizard.recalculo.masivo` | `catastro_predio.group_catastro_manager` | CRUD |
