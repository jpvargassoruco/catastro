# -*- coding: utf-8 -*-
from odoo import models, fields, api
from odoo.exceptions import ValidationError, UserError


class CatastroAvaluo(models.Model):
    _name = 'catastro.avaluo'
    _description = 'Avalúo Catastral'
    _inherit = ['mail.thread', 'mail.activity.mixin']
    _order = 'gestion desc, name desc'
    _rec_name = 'name'

    # ── Identificación ──────────────────────────────────────────────────────
    name = fields.Char(
        string='Código',
        readonly=True,
        copy=False,
        default='Nuevo',
    )
    predio_id = fields.Many2one(
        comodel_name='catastro.predio',
        string='Predio',
        required=True,
        ondelete='restrict',
        tracking=True,
        index=True,
    )
    # Related para mostrar datos del predio sin navegar
    clave_catastral = fields.Char(
        related='predio_id.clave_catastral',
        string='Clave Catastral',
        store=True,
    )
    propietario_id = fields.Many2one(
        related='predio_id.propietario_id',
        string='Propietario',
        store=True,
    )
    zona = fields.Char(
        related='predio_id.zona',
        string='Zona',
        store=True,
    )

    gestion = fields.Integer(
        string='Gestión (Año)',
        required=True,
        default=lambda self: fields.Date.today().year,
        tracking=True,
    )
    tabla_valor_id = fields.Many2one(
        comodel_name='catastro.tabla.valor',
        string='Tabla de Valores Aplicada',
        required=True,
        tracking=True,
    )

    # ── Snapshots al momento del avalúo ─────────────────────────────────────
    superficie_terreno = fields.Float(
        string='Superficie Terreno (m²)',
        digits=(12, 2),
    )
    superficie_construida = fields.Float(
        string='Superficie Construida (m²)',
        digits=(12, 2),
    )
    valor_unitario_terreno = fields.Float(
        string='VU Terreno (Bs/m²)',
        digits=(12, 2),
    )
    valor_unitario_construccion = fields.Float(
        string='VU Construcción (Bs/m²)',
        digits=(12, 2),
    )
    factor_estado = fields.Float(
        string='Factor Estado de Conservación',
        default=1.0,
        digits=(4, 2),
        help=(
            'Factor de depreciación por estado de conservación:\n'
            '1.0 = Muy bueno | 0.85 = Bueno | 0.70 = Regular | 0.50 = Malo'
        ),
    )

    # ── Valores calculados ───────────────────────────────────────────────────
    valor_terreno = fields.Float(
        string='Valor Terreno (Bs)',
        compute='_compute_valores',
        store=True,
        digits=(14, 2),
        tracking=True,
    )
    valor_construccion = fields.Float(
        string='Valor Construcción (Bs)',
        compute='_compute_valores',
        store=True,
        digits=(14, 2),
        tracking=True,
    )
    valor_catastral = fields.Float(
        string='Valor Catastral Total (Bs)',
        compute='_compute_valores',
        store=True,
        digits=(14, 2),
        tracking=True,
    )

    # ── Estado y fechas ──────────────────────────────────────────────────────
    state = fields.Selection(
        selection=[
            ('calculado', 'Calculado'),
            ('aprobado', 'Aprobado'),
            ('vigente', 'Vigente'),
            ('historico', 'Histórico'),
        ],
        string='Estado',
        default='calculado',
        required=True,
        tracking=True,
    )
    fecha_calculo = fields.Date(
        string='Fecha de Cálculo',
        default=fields.Date.today,
    )
    fecha_aprobacion = fields.Date(string='Fecha de Aprobación')
    notas = fields.Text(string='Observaciones del Valuador')

    # ── SQL constraints ──────────────────────────────────────────────────────
    _sql_constraints = [
        (
            'unique_predio_gestion',
            'UNIQUE(predio_id, gestion)',
            'Ya existe un avalúo para este predio en la misma gestión.',
        ),
    ]

    # ── Compute / Onchange ───────────────────────────────────────────────────
    @api.depends(
        'superficie_terreno', 'valor_unitario_terreno',
        'superficie_construida', 'valor_unitario_construccion', 'factor_estado',
    )
    def _compute_valores(self):
        for rec in self:
            rec.valor_terreno = rec.superficie_terreno * rec.valor_unitario_terreno
            rec.valor_construccion = (
                rec.superficie_construida
                * rec.valor_unitario_construccion
                * rec.factor_estado
            )
            rec.valor_catastral = rec.valor_terreno + rec.valor_construccion

    @api.onchange('predio_id')
    def _onchange_predio(self):
        """Cargar superficies del predio automáticamente."""
        if self.predio_id:
            self.superficie_terreno = self.predio_id.superficie_terreno
            self.superficie_construida = self.predio_id.superficie_construida

    @api.onchange('tabla_valor_id')
    def _onchange_tabla_valor(self):
        """Cargar valores unitarios de la tabla seleccionada."""
        if self.tabla_valor_id:
            self.valor_unitario_terreno = self.tabla_valor_id.valor_terreno
            self.valor_unitario_construccion = self.tabla_valor_id.valor_construccion

    @api.constrains('factor_estado')
    def _check_factor_estado(self):
        for rec in self:
            if not (0.0 <= rec.factor_estado <= 1.0):
                raise ValidationError(
                    'El factor de estado de conservación debe estar entre 0.0 y 1.0.'
                )

    # ── Acciones de flujo ────────────────────────────────────────────────────
    def action_aprobar(self):
        """Aprobar el avalúo calculado."""
        for rec in self:
            if rec.state != 'calculado':
                raise UserError('Solo se pueden aprobar avalúos en estado Calculado.')
            rec.write({
                'state': 'aprobado',
                'fecha_aprobacion': fields.Date.today(),
            })

    def action_vigente(self):
        """Marcar como vigente (archiva el anterior vigente del predio)."""
        for rec in self:
            if rec.state not in ('calculado', 'aprobado'):
                raise UserError('Solo se pueden activar avalúos Calculados o Aprobados.')
            # Marcar el vigente actual como histórico
            rec.predio_id.avaluo_ids.filtered(
                lambda a: a.state == 'vigente' and a.id != rec.id
            ).write({'state': 'historico'})
            rec.write({'state': 'vigente'})

    def action_historico(self):
        """Archivar manualmente un avalúo como histórico."""
        for rec in self:
            rec.write({'state': 'historico'})

    # ── Creación con secuencia ───────────────────────────────────────────────
    @api.model_create_multi
    def create(self, vals_list):
        for vals in vals_list:
            if vals.get('name', 'Nuevo') == 'Nuevo':
                vals['name'] = (
                    self.env['ir.sequence'].next_by_code('catastro.avaluo') or 'AV-NEW'
                )
        return super().create(vals_list)
