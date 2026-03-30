# -*- coding: utf-8 -*-
from odoo import models, fields, api
from odoo.exceptions import ValidationError
from odoo import _


class CatastroEdificacion(models.Model):
    _name = 'catastro.edificacion'
    _description = 'Edificación sobre Predio Catastral'
    _order = 'predio_id, tipo_construccion'

    predio_id = fields.Many2one(
        comodel_name='catastro.predio',
        string='Predio',
        required=True,
        ondelete='cascade',
        index=True,
    )
    tipo_construccion = fields.Selection(
        selection=[
            ('vivienda', 'Vivienda'),
            ('comercio', 'Local Comercial'),
            ('industria', 'Nave Industrial'),
            ('deposito', 'Depósito / Almacén'),
            ('garaje', 'Garaje'),
            ('otro', 'Otro'),
        ],
        string='Tipo de Construcción',
        required=True,
        default='vivienda',
    )
    descripcion = fields.Char(string='Descripción')
    superficie = fields.Float(
        string='Superficie (m²)',
        digits=(10, 2),
        required=True,
    )
    niveles = fields.Integer(
        string='Número de Plantas/Niveles',
        default=1,
    )

    # ── Características constructivas ────────────────────────────────────────
    material_estructura = fields.Selection(
        selection=[
            ('hormigon', 'Hormigón Armado'),
            ('ladrillo', 'Ladrillo/Adobe'),
            ('madera', 'Madera'),
            ('metalica', 'Estructura Metálica'),
            ('mixta', 'Mixta'),
        ],
        string='Material Estructura',
    )
    material_cubierta = fields.Selection(
        selection=[
            ('hormigon', 'Losa de Hormigón'),
            ('teja', 'Teja (cerámica/fibrocemento)'),
            ('calamina', 'Calamina/Zinc'),
            ('madera', 'Madera'),
            ('paja', 'Paja/Palma'),
        ],
        string='Material Cubierta',
    )
    estado_conservacion = fields.Selection(
        selection=[
            ('muy_bueno', 'Muy Bueno'),
            ('bueno', 'Bueno'),
            ('regular', 'Regular'),
            ('malo', 'Malo'),
            ('ruinoso', 'Ruinoso'),
        ],
        string='Estado de Conservación',
        default='bueno',
    )
    anio_construccion = fields.Integer(string='Año de Construcción')

    # ── Valor ────────────────────────────────────────────────────────────────
    currency_id = fields.Many2one(
        comodel_name='res.currency',
        default=lambda self: self.env.company.currency_id,
    )
    valor_unitario = fields.Monetary(
        string='Valor Unitario (Bs/m²)',
        currency_field='currency_id',
    )
    valor = fields.Monetary(
        string='Valor Construcción (Bs)',
        currency_field='currency_id',
        compute='_compute_valor',
        store=True,
    )

    @api.depends('superficie', 'valor_unitario')
    def _compute_valor(self):
        for rec in self:
            rec.valor = rec.superficie * rec.valor_unitario

    @api.constrains('superficie', 'niveles')
    def _check_valores(self):
        for rec in self:
            if rec.superficie <= 0:
                raise ValidationError(_('La superficie de la edificación debe ser mayor a cero.'))
            if rec.niveles < 1:
                raise ValidationError(_('El número de niveles debe ser al menos 1.'))
