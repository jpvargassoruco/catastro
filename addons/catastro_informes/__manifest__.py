# -*- coding: utf-8 -*-
{
    'name': 'Catastro - Informes Técnicos',
    'version': '17.0.1.0.0',
    'summary': 'Gestión de informes de inspección, linderos y nivel de cotas',
    'description': """
Módulo de Catastro Municipal - Informes
=======================================
Controla la generación de documentos técnicos requeridos por Obras Públicas,
tales como Líneas de Nivel, Informes de Empadronamiento y Avalúo Físico.
Difiere de los certificados en que requiere manipulación pericial de datos
antes de ser emitido.
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
        'views/catastro_informe_views.xml',
        'reports/informe_empadronamiento.xml',
        'reports/linea_nivel_report.xml',
    ],
    'installable': True,
    'application': False,
    'auto_install': False,
}
