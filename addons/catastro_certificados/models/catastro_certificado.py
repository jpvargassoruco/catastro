# -*- coding: utf-8 -*-

from odoo import models, fields

class CatastroCertificado(models.Model):
    _name = 'catastro.certificado'
    _description = 'Registro de Emisión de Certificado'
    
    name = fields.Char(string='Folio/Código de Certificado', required=True, default='Nuevo')
    predio_id = fields.Many2one('catastro.predio', string='Predio Vinculado', required=True)
    solicitante_id = fields.Many2one('res.partner', string='Propietario Solicitante', required=True)
    
    fecha_emision = fields.Date(string='Fecha de Emisión', default=fields.Date.context_today)
    valido_hasta = fields.Date(string='Válido Hasta')
    
    estado = fields.Selection([
        ('borrador', 'En Preparación'),
        ('impreso', 'Impreso / Entregado'),
        ('anulado', 'Anulado')
    ], string='Estado de Expedición', default='borrador', required=True)
