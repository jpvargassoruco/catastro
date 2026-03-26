# Auditoría de Bugs — `catastro_avaluo`

**Fecha:** 2026-03-26 | **Commit:** `5eaf254`

## Bugs corregidos

### 🔴 Bug 1 — Campo `activo` → `active`
**Archivo:** `models/catastro_tabla_valor.py` + `views/catastro_tabla_valor_views.xml`

El campo de archivado debe llamarse `active` para que Odoo active el archivado nativo (botón, filtros, `_active_domain`). Con `activo` era solo un booleano sin semántica especial.

```diff
- activo = fields.Boolean(string='Activo', default=True)
+ active = fields.Boolean(string='Activo', default=True,
+     help='Desactivar para archivar la tabla sin eliminarla.')
```

---

### 🔴 Bug 2 — `name_get()` eliminado en Odoo 17
**Archivo:** `models/catastro_tabla_valor.py`

En Odoo 17, `name_get()` fue reemplazado por `_compute_display_name`.

```diff
- def name_get(self):
-     return [(rec.id, rec.name) for rec in self]
+ def _compute_display_name(self):
+     for rec in self:
+         rec.display_name = rec.name or 'Tabla de valores'
```

---

### 🔴 Bug 3 — Campo `tipo` inexistente en wizard
**Archivo:** `wizard/wizard_recalculo_masivo.py` → `_get_predios()`

El dominio usaba `tipo` pero `catastro.predio` tiene `tipo_predio`. El filtro se ignoraba silenciosamente.

```diff
- domain += [('tipo', '=', self.tipo_predio)]
+ domain += [('tipo_predio', '=', self.tipo_predio)]
```

---

### 🔴 Bug 4 — `predio_id` en `write()` viola constraint UNIQUE
**Archivo:** `wizard/wizard_recalculo_masivo.py` → `action_recalcular()`

El dict `vals` se reutilizaba para `create()` y `write()`, incluyendo `predio_id` en el write. Separado en `vals_tabla` (sin `predio_id`) + campos específicos por operación.

---

### 🟡 Bug 5 — `@api.depends` faltante en `_compute_avaluo_count`
**Archivo:** `models/catastro_predio.py`

Sin `@api.depends('avaluo_ids')`, Odoo no sabe cuándo invalida el caché del campo computado.

```diff
+ @api.depends('avaluo_ids')
  def _compute_avaluo_count(self):
```

---

### 🟡 Bug 6 — `action_ver_avaluos` sin `ensure_one()`
**Archivo:** `models/catastro_predio.py`

Método que accede a `self.id` sin garantizar singleton.

```diff
  def action_ver_avaluos(self):
+     self.ensure_one()
```

---

### 🟡 Bug 7 — `widget="monetary"` sin `currency_id`
**Archivo:** `views/catastro_avaluo_views.xml`

El widget `monetary` requiere un campo `currency_id` en el modelo o lanza error al renderizar. El modelo trabaja en Bs sin ese campo.

```diff
- <field name="valor_catastral" readonly="1"
-        widget="monetary" options="{'currency_field': 'currency_id'}"/>
+ <field name="valor_catastral" readonly="1"/>
```
