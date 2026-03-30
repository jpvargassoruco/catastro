# -*- coding: utf-8 -*-
{
    'name': 'Catastro - Gravámenes e Hipotecas',
    'version': '17.0.1.0.0',
    'summary': 'Registro de cargas y restricciones sobre predios municipales',
    'description': """
Módulo de Catastro Municipal - Gravámenes
=========================================
Subsistema de seguridad jurídica para el registro de anotaciones preventivas,
hipotecas judiciales, prendarias y gravámenes bancarios de predios y contribuyentes.
    """,
    'author': 'Gobierno Autónomo Municipal de Vallegrande',
    'website': 'https://github.com/Pothoko/catastro_01',
    'category': 'Government/Cadastre',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'catastro_predio',
    ],
    'data': [
        'security/ir.model.access.csv',
        'views/catastro_gravamen_views.xml',
    ],
    'installable': True,
    'application': False,
    'auto_install': False,
}
