# -*- coding: utf-8 -*-

from odoo import models, fields

class CatastroGravamen(models.Model):
    _name = 'catastro.gravamen'
    _description = 'Gravamen de Predio'

    name = fields.Char(string='Referencia Legal / Resolucion', required=True, copy=False)
    predio_id = fields.Many2one('catastro.predio', string='Predio Vinculado', required=True)
    acreedor_id = fields.Many2one('res.partner', string='Acreedor (Banco/Juez)', required=True)
    
    fecha_inscripcion = fields.Date(string='Fecha de Inscripción', default=fields.Date.context_today)
    monto_deuda = fields.Float(string='Monto Gravado (Opcional)')
    descripcion = fields.Text(string='Observaciones Adicionales')
    
    estado = fields.Selection([
        ('activo', 'Activo (Vigente)'),
        ('levantado', 'Levantado / Cancelado')
    ], string='Estado Legal', default='activo', required=True)
