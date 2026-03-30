# -*- coding: utf-8 -*-
{
    'name': 'Catastro - Planos y Cartografía',
    'version': '17.0.1.0.0',
    'summary': 'Visor geográfico, planimetría y capas cartográficas sobre predios',
    'description': """
Módulo de Catastro Municipal - Mapa GIS
=======================================
Integra los linderos, polígonos y vértices en un visor web map. 
Sustituye a MapServer manejando coordinadas geoespaciales 
(PostGIS) y permitiendo la interoperabilidad con Leaflet/OpenLayers en Odoo.
    """,
    'author': 'Gobierno Autónomo Municipal de Vallegrande',
    'website': 'https://github.com/Pothoko/catastro_01',
    'category': 'Government/Cadastre',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'catastro_predio',
        'web'
    ],
    'data': [
        'security/ir.model.access.csv',
        'views/catastro_mapa_views.xml',
    ],
    'assets': {
        'web.assets_backend': [
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            'catastro_mapa/static/src/js/map_widget.js',
            'catastro_mapa/static/src/xml/map_widget.xml',
        ],
    },
    'installable': True,
    'application': False,
    'auto_install': False,
}
