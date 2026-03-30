# -*- coding: utf-8 -*-
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from odoo import _


class CatastroTradicion(models.Model):
    _name = 'catastro.tradicion'
    _description = 'Tradición de Dominio - Historial de Propietarios'
    _order = 'predio_id, fecha_adquisicion desc'

    predio_id = fields.Many2one(
        comodel_name='catastro.predio',
        string='Predio',
        required=True,
        ondelete='cascade',
        index=True,
    )
    propietario_id = fields.Many2one(
        comodel_name='res.partner',
        string='Propietario',
        required=True,
    )
    tipo_operacion = fields.Selection(
        selection=[
            ('compraventa', 'Compraventa'),
            ('donacion', 'Donación'),
            ('herencia', 'Herencia/Sucesión'),
            ('adjudicacion', 'Adjudicación Judicial'),
            ('expropiacion', 'Expropiación'),
            ('permuta', 'Permuta'),
            ('otro', 'Otro'),
        ],
        string='Tipo de Operación',
        required=True,
    )
    fecha_adquisicion = fields.Date(
        string='Fecha de Adquisición',
        required=True,
    )
    fecha_transferencia = fields.Date(
        string='Fecha de Transferencia (salida)',
        help='Fecha en que cedió la propiedad al siguiente titular',
    )
    escritura_publica = fields.Char(
        string='Nro. Escritura Pública',
    )
    notaria = fields.Char(
        string='Notaría',
    )
    precio_transferencia = fields.Monetary(
        string='Precio de Transferencia (Bs)',
        currency_field='currency_id',
    )
    currency_id = fields.Many2one(
        comodel_name='res.currency',
        default=lambda self: self.env.company.currency_id,
    )
    notas = fields.Text(string='Observaciones')
    es_propietario_actual = fields.Boolean(
        string='Propietario Actual',
        compute='_compute_es_actual',
        help='Calculado: sin fecha de transferencia',
    )

    @api.depends('fecha_transferencia')
    def _compute_es_actual(self):
        for rec in self:
            rec.es_propietario_actual = not rec.fecha_transferencia

    @api.constrains('fecha_adquisicion', 'fecha_transferencia')
    def _check_fechas(self):
        for rec in self:
            if rec.fecha_transferencia and rec.fecha_adquisicion:
                if rec.fecha_transferencia < rec.fecha_adquisicion:
                    raise ValidationError(
                        _('La fecha de transferencia no puede ser anterior a la de adquisición.')
                    )
