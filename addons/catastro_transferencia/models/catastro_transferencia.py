# -*- coding: utf-8 -*-

from odoo import models, fields, api

class CatastroTransferencia(models.Model):
    _name = 'catastro.transferencia'
    _description = 'Transferencia de Dominio'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(string='Nº Trámite', required=True, copy=False, default='Nuevo')
    predio_id = fields.Many2one('catastro.predio', string='Predio', required=True)
    comprador_id = fields.Many2one('res.partner', string='Nuevo Propietario (Comprador)', required=True)
    vendedor_id = fields.Many2one('res.partner', string='Propietario Anterior (Vendedor)', required=True)
    
    fecha_solicitud = fields.Date(string='Fecha de Solicitud', default=fields.Date.context_today)
    monto_transferencia = fields.Float(string='Valor de Transferencia')
    
    estado = fields.Selection([
        ('borrador', 'Borrador'),
        ('en_revision', 'Documentación en Revisión'),
        ('liquidado', 'Impuesto Delineado'),
        ('aprobado', 'Transferencia Aprobada')
    ], string='Estado', default='borrador', tracking=True)
