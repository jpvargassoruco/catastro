# -*- coding: utf-8 -*-
from odoo import models, fields, api, _
from odoo.exceptions import ValidationError


class CatastroPredia(models.Model):
    _name = 'catastro.predio'
    _description = 'Predio Catastral'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'clave_catastral'
    _rec_name = 'clave_catastral'

    # ── Identificación ──────────────────────────────────────────────────────
    clave_catastral = fields.Char(
        string='Clave Catastral',
        required=True,
        copy=False,
        index=True,
        tracking=True,
        help='Código único del predio (ej: VG-U-001-002)',
    )
    name = fields.Char(
        string='Descripción',
        compute='_compute_name',
        store=True,
    )
    tipo = fields.Selection(
        selection=[('urbano', 'Urbano'), ('rural', 'Rural')],
        string='Tipo de Predio',
        required=True,
        default='urbano',
        tracking=True,
    )
    state = fields.Selection(
        selection=[
            ('borrador', 'Borrador'),
            ('registrado', 'Registrado'),
            ('inactivo', 'Inactivo'),
        ],
        string='Estado',
        default='borrador',
        required=True,
        tracking=True,
    )

    # ── Ubicación ───────────────────────────────────────────────────────────
    zona = fields.Char(string='Zona', tracking=True)
    sector = fields.Char(string='Sector')
    manzano = fields.Char(string='Manzano', help='Sólo para predios urbanos')
    lote = fields.Char(string='Nro. Lote')
    direccion = fields.Char(string='Dirección / Paraje', tracking=True)
    distrito = fields.Char(string='Distrito')
    comunidad = fields.Char(string='Comunidad', help='Sólo para predios rurales')

    # ── Propietario ─────────────────────────────────────────────────────────
    propietario_id = fields.Many2one(
        comodel_name='res.partner',
        string='Propietario Actual',
        tracking=True,
        index=True,
    )
    copropietario_ids = fields.Many2many(
        comodel_name='res.partner',
        relation='catastro_predio_copropietario_rel',
        column1='predio_id',
        column2='partner_id',
        string='Copropietarios',
    )

    # ── Uso de suelo ─────────────────────────────────────────────────────────
    uso_suelo = fields.Selection(
        selection=[
            ('residencial', 'Residencial'),
            ('comercial', 'Comercial'),
            ('industrial', 'Industrial'),
            ('institucional', 'Institucional'),
            ('agricola', 'Agrícola'),
            ('ganadero', 'Ganadero'),
            ('mixto', 'Mixto'),
            ('otro', 'Otro'),
        ],
        string='Uso de Suelo',
        tracking=True,
    )
    clasificacion_suelo = fields.Selection(
        selection=[
            ('urbano', 'Suelo Urbano'),
            ('urbanizable', 'Urbanizable'),
            ('no_urbanizable', 'No Urbanizable'),
        ],
        string='Clasificación de Suelo',
    )

    # ── Superficies ──────────────────────────────────────────────────────────
    superficie_terreno = fields.Float(
        string='Superficie Terreno (m²)',
        digits=(12, 2),
        tracking=True,
    )
    superficie_construida = fields.Float(
        string='Superficie Construida (m²)',
        digits=(12, 2),
        compute='_compute_superficie_construida',
        store=True,
    )
    frente = fields.Float(string='Frente (m)', digits=(8, 2))
    fondo = fields.Float(string='Fondo (m)', digits=(8, 2))

    # ── Valores catastrales ──────────────────────────────────────────────────
    currency_id = fields.Many2one(
        comodel_name='res.currency',
        string='Moneda',
        default=lambda self: self.env.company.currency_id,
    )
    valor_unitario_terreno = fields.Monetary(
        string='Valor Unitario Terreno (Bs/m²)',
        currency_field='currency_id',
    )
    valor_terreno = fields.Monetary(
        string='Valor Catastral Terreno (Bs)',
        currency_field='currency_id',
        compute='_compute_valor_terreno',
        store=True,
        tracking=True,
    )
    valor_construccion = fields.Monetary(
        string='Valor Catastral Construcción (Bs)',
        currency_field='currency_id',
        compute='_compute_valor_construccion',
        store=True,
        tracking=True,
    )
    valor_total = fields.Monetary(
        string='Valor Catastral Total (Bs)',
        currency_field='currency_id',
        compute='_compute_valor_total',
        store=True,
    )

    # ── Relaciones ───────────────────────────────────────────────────────────
    colindante_ids = fields.One2many(
        comodel_name='catastro.colindante',
        inverse_name='predio_id',
        string='Colindantes',
    )
    tradicion_ids = fields.One2many(
        comodel_name='catastro.tradicion',
        inverse_name='predio_id',
        string='Tradición de Dominio',
    )
    edificacion_ids = fields.One2many(
        comodel_name='catastro.edificacion',
        inverse_name='predio_id',
        string='Edificaciones',
    )

    # ── Notas / Grabámenes ──────────────────────────────────────────────────
    notas = fields.Text(string='Observaciones')
    tiene_gravamen = fields.Boolean(
        string='Tiene Gravamen',
        compute='_compute_tiene_gravamen',
        store=True,
    )

    # ── Computes ─────────────────────────────────────────────────────────────

    @api.depends('clave_catastral', 'propietario_id')
    def _compute_name(self):
        for rec in self:
            parts = [rec.clave_catastral or '']
            if rec.propietario_id:
                parts.append(rec.propietario_id.name)
            rec.name = ' — '.join(filter(None, parts))

    @api.depends('edificacion_ids.superficie')
    def _compute_superficie_construida(self):
        for rec in self:
            rec.superficie_construida = sum(
                rec.edificacion_ids.mapped('superficie')
            )

    @api.depends('superficie_terreno', 'valor_unitario_terreno')
    def _compute_valor_terreno(self):
        for rec in self:
            rec.valor_terreno = rec.superficie_terreno * rec.valor_unitario_terreno

    @api.depends('edificacion_ids.valor')
    def _compute_valor_construccion(self):
        for rec in self:
            rec.valor_construccion = sum(
                rec.edificacion_ids.mapped('valor')
            )

    @api.depends('valor_terreno', 'valor_construccion')
    def _compute_valor_total(self):
        for rec in self:
            rec.valor_total = rec.valor_terreno + rec.valor_construccion

    @api.depends('notas')
    def _compute_tiene_gravamen(self):
        # Será extendido por catastro_gravamen en el futuro
        for rec in self:
            rec.tiene_gravamen = False

    # ── Constraints ──────────────────────────────────────────────────────────

    _sql_constraints = [
        (
            'clave_catastral_unique',
            'UNIQUE(clave_catastral)',
            'La clave catastral debe ser única.',
        ),
    ]

    @api.constrains('superficie_terreno')
    def _check_superficie(self):
        for rec in self:
            if rec.superficie_terreno < 0:
                raise ValidationError(
                    _('La superficie del terreno no puede ser negativa.')
                )

    # ── Actions ──────────────────────────────────────────────────────────────

    def action_registrar(self):
        """Confirmar el registro del predio."""
        for rec in self:
            rec.state = 'registrado'

    def action_inactivar(self):
        """Marcar el predio como inactivo."""
        for rec in self:
            rec.state = 'inactivo'

    def action_borrador(self):
        """Volver a estado borrador."""
        for rec in self:
            rec.state = 'borrador'
