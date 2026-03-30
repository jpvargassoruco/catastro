# -*- coding: utf-8 -*-
from odoo import models, fields, api


class CatastroTablaValor(models.Model):
    _name = 'catastro.tabla.valor'
    _description = 'Tabla de Valores Unitarios Catastrales'
    _order = 'gestion desc, zona, uso_suelo'

    # ── Identificación ──────────────────────────────────────────────────────
    name = fields.Char(
        string='Denominación',
        compute='_compute_name',
        store=True,
    )
    zona = fields.Char(
        string='Zona',
        required=True,
        help='Zona catastral (debe coincidir con la zona del predio)',
    )
    uso_suelo = fields.Selection(
        selection=[
            ('residencial', 'Residencial'),
            ('comercial', 'Comercial'),
            ('industrial', 'Industrial'),
            ('agricola', 'Agrícola'),
            ('mixto', 'Mixto'),
        ],
        string='Uso de Suelo',
        required=True,
    )
    tipo_predio = fields.Selection(
        selection=[('urbano', 'Urbano'), ('rural', 'Rural')],
        string='Tipo de Predio',
        required=True,
        default='urbano',
    )
    gestion = fields.Integer(
        string='Gestión (Año)',
        required=True,
        default=lambda self: fields.Date.today().year,
    )
    active = fields.Boolean(
        string='Activo',
        default=True,
        help='Desactivar para archivar la tabla sin eliminarla.',
    )

    # ── Valores unitarios ────────────────────────────────────────────────────
    valor_terreno = fields.Float(
        string='VU Terreno (Bs/m²)',
        required=True,
        digits=(12, 2),
        help='Valor unitario de terreno en Bolivianos por metro cuadrado',
    )
    valor_construccion = fields.Float(
        string='VU Construcción (Bs/m²)',
        required=True,
        digits=(12, 2),
        help='Valor unitario base de construcción en Bs/m²',
    )
    notas = fields.Text(string='Notas / Fundamento Legal')

    # ── SQL constraints ──────────────────────────────────────────────────────
    _sql_constraints = [
        (
            'unique_zona_uso_tipo_gestion',
            'UNIQUE(zona, uso_suelo, tipo_predio, gestion)',
            'Ya existe una tabla de valores para esta zona, uso de suelo, '
            'tipo de predio y gestión.',
        ),
    ]

    # ── Compute ──────────────────────────────────────────────────────────────
    @api.depends('zona', 'uso_suelo', 'gestion')
    def _compute_name(self):
        uso_labels = dict(self._fields['uso_suelo'].selection)
        for rec in self:
            zona = rec.zona or '?'
            uso = uso_labels.get(rec.uso_suelo, '?')
            rec.name = f'Zona {zona} / {uso} {rec.gestion or ""}'

    # ── Display name (Odoo 17: _compute_display_name, no name_get) ──────────
    def _compute_display_name(self):
        """name_get() está deprecated desde Odoo 17; se usa este override."""
        for rec in self:
            rec.display_name = rec.name or 'Tabla de valores'
