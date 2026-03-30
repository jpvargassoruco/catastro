# -*- coding: utf-8 -*-
from odoo import models, fields, api
from odoo.exceptions import UserError


class WizardRecalculoMasivo(models.TransientModel):
    _name = 'catastro.wizard.recalculo'
    _description = 'Recálculo Masivo de Avalúos Catastrales'

    gestion = fields.Integer(
        string='Gestión (Año)',
        required=True,
        default=lambda self: fields.Date.today().year,
        help='Año para el cual se generarán/actualizarán los avalúos.',
    )
    tabla_valor_id = fields.Many2one(
        comodel_name='catastro.tabla.valor',
        string='Tabla de Valores a Aplicar',
        required=True,
    )
    # Filtros opcionales
    zona = fields.Char(
        string='Zona',
        help='Dejar vacío para procesar todas las zonas de la tabla seleccionada.',
    )
    tipo_predio = fields.Selection(
        selection=[('urbano', 'Urbano'), ('rural', 'Rural'), ('todos', 'Todos')],
        string='Tipo de Predio',
        default='todos',
    )
    solo_sin_avaluo = fields.Boolean(
        string='Solo predios sin avalúo en esta gestión',
        default=False,
        help='Si está marcado, omite los predios que ya tienen avalúo para la gestión seleccionada.',
    )

    # ── Información de previsualización ─────────────────────────────────────
    predios_count = fields.Integer(
        string='Predios a procesar',
        compute='_compute_predios_count',
    )

    @api.depends('zona', 'tipo_predio', 'gestion', 'solo_sin_avaluo')
    def _compute_predios_count(self):
        for rec in self:
            rec.predios_count = len(rec._get_predios())

    def _get_predios(self):
        domain = [('state', '=', 'registrado')]
        if self.zona:
            domain += [('zona', '=', self.zona)]
        if self.tipo_predio and self.tipo_predio != 'todos':
            # catastro.predio usa el campo 'tipo_predio', no 'tipo'
            domain += [('tipo_predio', '=', self.tipo_predio)]

        predios = self.env['catastro.predio'].search(domain)

        if self.solo_sin_avaluo and self.gestion:
            existentes_ids = self.env['catastro.avaluo'].search([
                ('predio_id', 'in', predios.ids),
                ('gestion', '=', self.gestion),
            ]).mapped('predio_id').ids
            predios = predios.filtered(lambda p: p.id not in existentes_ids)

        return predios

    def action_recalcular(self):
        """Generar o actualizar avalúos para los predios filtrados."""
        self.ensure_one()
        if not self.tabla_valor_id:
            raise UserError('Debe seleccionar una tabla de valores.')

        predios = self._get_predios()
        if not predios:
            raise UserError('No se encontraron predios con los filtros seleccionados.')

        creados = 0
        actualizados = 0
        # Valores comunes derivados de la tabla (vals de actualización sin predio_id)
        vals_tabla = {
            'gestion': self.gestion,
            'tabla_valor_id': self.tabla_valor_id.id,
            'valor_unitario_terreno': self.tabla_valor_id.valor_terreno,
            'valor_unitario_construccion': self.tabla_valor_id.valor_construccion,
            'fecha_calculo': fields.Date.today(),
        }
        for predio in predios:
            existente = self.env['catastro.avaluo'].search([
                ('predio_id', '=', predio.id),
                ('gestion', '=', self.gestion),
            ], limit=1)
            if existente and existente.state == 'calculado':
                # No incluir predio_id en write() — ya está asignado y
                # cambiar predio viola el unique constraint
                existente.write(dict(vals_tabla, **{
                    'superficie_terreno': predio.superficie_terreno,
                    'superficie_construida': predio.superficie_construida,
                }))
                actualizados += 1
            elif not existente:
                self.env['catastro.avaluo'].create(dict(vals_tabla, **{
                    'predio_id': predio.id,
                    'superficie_terreno': predio.superficie_terreno,
                    'superficie_construida': predio.superficie_construida,
                }))
                creados += 1

        return {
            'type': 'ir.actions.client',
            'tag': 'display_notification',
            'params': {
                'title': 'Recálculo completado',
                'message': f'Se crearon {creados} avalúos y se actualizaron {actualizados}.',
                'type': 'success',
                'sticky': False,
            },
        }
