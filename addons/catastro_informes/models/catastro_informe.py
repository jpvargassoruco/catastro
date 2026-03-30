# -*- coding: utf-8 -*-

from odoo import models, fields

class CatastroInforme(models.Model):
    _name = 'catastro.informe'
    _description = 'Informe Técnico de Campo'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(string='Número de Emisión / Hoja Ruta', required=True, copy=False, default='Nuevo')
    predio_id = fields.Many2one('catastro.predio', string='Predio Evaluado', required=True)
    tecnico_id = fields.Many2one('res.users', string='Técnico Responsable', default=lambda self: self.env.user)
    
    fecha_emision = fields.Date(string='Fecha Evaluación', default=fields.Date.context_today)
    
    tipo_informe = fields.Selection([
        ('linea_nivel', 'Asignación de Línea y Cota de Nivel'),
        ('empadronamiento', 'Informe Técnico de Empadronamiento Oficial')
    ], string='Tipo de Certificación Técnica', required=True)
    
    conclusiones = fields.Text(string='Conclusiones Técnicas')
    
    estado = fields.Selection([
        ('borrador', 'En Campo / Procesando'),
        ('finalizado', 'Validado y Finalizado')
    ], string='Estado de Expediente', default='borrador', tracking=True)
