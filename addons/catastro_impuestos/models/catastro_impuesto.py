# -*- coding: utf-8 -*-

from odoo import models, fields, api
from odoo.exceptions import UserError

class CatastroImpuesto(models.Model):
    _name = 'catastro.impuesto'
    _description = 'Impuesto Predial Anual'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    name = fields.Char(string='Referencia / Boleta', required=True, copy=False, default='Borrador')
    predio_id = fields.Many2one('catastro.predio', string='Predio a Tributar', required=True, tracking=True)
    
    # Rango de años de 1990 en adelante, emulando la BD SIICAT
    gestion = fields.Selection([(str(y), str(y)) for y in range(1990, 2030)], string='Gestión Tributaria (Año)', required=True, default=fields.Date.context_today)
    
    # Valores Impositivos base
    base_imponible = fields.Float(string='Base Imponible Estimada (BOB)', compute='_compute_impuestos', store=True, tracking=True)
    impuesto_calculado = fields.Float(string='Impuesto Determinado', compute='_compute_impuestos', store=True)
    
    # Depreciaciones y descuentos de ley emulados desde siicat_impuestos_calc.php
    descuento_ley = fields.Float(string='Descuento de Ley', default=0.0)
    multa_mora = fields.Float(string='Multa por Mora', default=0.0)
    
    monto_pagar = fields.Float(string='Total a Pagar', compute='_compute_impuestos', store=True, tracking=True)
    
    estado = fields.Selection([
        ('borrador', 'Proforma Liquidación'),
        ('liquidado', 'Liquidado / Listo para Pagar'),
        ('pagado', 'Pagado / Exonerado')
    ], string='Estado de Recaudación', default='borrador', tracking=True)
    
    factura_id = fields.Many2one('account.move', string='Factura Contable (Caja)', readonly=True)

    @api.depends('predio_id', 'gestion', 'descuento_ley', 'multa_mora')
    def _compute_impuestos(self):
        for record in self:
            monto_base = 0.0
            if record.predio_id:
                # La lógica final sumará la superficie de terreno por su valor zonal
                # Más la superficie construida ajustada por la tabla de depreciación municipal.
                area = record.predio_id.area_terreno_title or record.predio_id.area_terreno_mensura or 0.0
                # Valor catastral teórica de 55 BOB / mt2 para emular un cálculo rápido
                monto_base = area * 55.0  

            # Tasa impositiva municipal genérica del 0.35% (Ley Municipal)
            impuesto_fijo = monto_base * 0.0035 
            
            record.base_imponible = monto_base
            record.impuesto_calculado = impuesto_fijo
            
            # El SIICAT tomaba el impuesto calculado, restaba el descuento y sumaba mora
            record.monto_pagar = impuesto_fijo - record.descuento_ley + record.multa_mora

    def action_aprobar_liquidacion(self):
        """Bloquea el recálculo y permite a caja cobrar."""
        for rec in self:
            if rec.monto_pagar <= 0:
                raise UserError("El monto a pagar debe ser mayor a 0.")
            rec.name = f"LIQ/{rec.gestion}/{rec.predio_id.codigo_catastral or rec.predio_id.id}"
            rec.estado = 'liquidado'
        
    def action_generar_factura(self):
        """Integra nativamente con el módulo account de Odoo."""
        for rec in self:
            if not rec.predio_id.propietario_id:
                raise UserError("El predio debe tener un propietario ('propietario_id') asignado para emitir la factura en Caja.")
            
            move_vals = {
                'move_type': 'out_invoice',
                'partner_id': rec.predio_id.propietario_id.id,
                'invoice_date': fields.Date.context_today(self),
                'invoice_line_ids': [(0, 0, {
                    'name': f'Impuesto Predial Gestión {rec.gestion} - Predio: {rec.predio_id.codigo_catastral}',
                    'quantity': 1,
                    'price_unit': rec.monto_pagar,
                    # Impuestos Odoo nativos (IVA, etc) podrían setearse aquí
                })],
            }
            factura = self.env['account.move'].create(move_vals)
            rec.factura_id = factura.id
            rec.estado = 'pagado'
