# -*- coding: utf-8 -*-

from odoo import models, fields

class CatastroMapa(models.Model):
    _name = 'catastro.mapa'
    _description = 'Capa de Mapa Catastral'

    name = fields.Char(string='Nombre de la Capa / Plano', required=True)
    predio_id = fields.Many2one('catastro.predio', string='Predio Principal Vinculado')
    
    tipo_poligono = fields.Selection([
        ('predio', 'Límites del Predio'),
        ('construccion', 'Polígono de Construcción'),
        ('ochave', 'Curva/Ochave'),
        ('eje', 'Eje de Calle')
    ], string='Tipo Geométrico', required=True, default='predio')
    
    coordenadas_json = fields.Text(string='GeoJSON / Coordenadas Base', help="Almacena el formato crudo para el visor Leaflet u OpenLayers.")
    
    activo = fields.Boolean(string='Capa Visible', default=True)
