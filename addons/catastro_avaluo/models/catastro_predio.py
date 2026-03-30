# -*- coding: utf-8 -*-
from odoo import models, fields, api


class CatastroPredioPPExt(models.Model):
    """Extensión del predio para agregar avalúos y valor catastral vigente."""

    _inherit = 'catastro.predio'

    # ── Relación con avalúos ─────────────────────────────────────────────────
    avaluo_ids = fields.One2many(
        comodel_name='catastro.avaluo',
        inverse_name='predio_id',
        string='Avalúos',
    )
    avaluo_count = fields.Integer(
        string='Nro. Avalúos',
        compute='_compute_avaluo_count',
    )

    # ── Avalúo vigente ───────────────────────────────────────────────────────
    avaluo_vigente_id = fields.Many2one(
        comodel_name='catastro.avaluo',
        string='Avalúo Vigente',
        compute='_compute_avaluo_vigente',
        store=True,
    )
    valor_catastral_vigente = fields.Float(
        string='Valor Catastral Vigente (Bs)',
        related='avaluo_vigente_id.valor_catastral',
        store=True,
        digits=(14, 2),
    )
    gestion_avaluo_vigente = fields.Integer(
        string='Gestión del Avalúo',
        related='avaluo_vigente_id.gestion',
        store=True,
    )

    # ── Computes ─────────────────────────────────────────────────────────────
    @api.depends('avaluo_ids', 'avaluo_ids.state')
    def _compute_avaluo_vigente(self):
        for rec in self:
            vigente = rec.avaluo_ids.filtered(lambda a: a.state == 'vigente')
            rec.avaluo_vigente_id = vigente[:1]

    @api.depends('avaluo_ids')
    def _compute_avaluo_count(self):
        for rec in self:
            rec.avaluo_count = len(rec.avaluo_ids)

    # ── Smart button action ───────────────────────────────────────────────────
    def action_ver_avaluos(self):
        self.ensure_one()
        return {
            'name': 'Avalúos del Predio',
            'type': 'ir.actions.act_window',
            'res_model': 'catastro.avaluo',
            'view_mode': 'list,form',
            'domain': [('predio_id', '=', self.id)],
            'context': {'default_predio_id': self.id},
        }
