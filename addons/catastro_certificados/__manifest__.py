# -*- coding: utf-8 -*-
{
    'name': 'Catastro - Certificados Catastrales',
    'version': '17.0.1.0.0',
    'summary': 'Emisión y registro de certificados formales Odoo QWeb',
    'description': """
Módulo de Catastro Municipal - Certificados
===========================================
Motor de plantillas QWeb para reemplazar el antiguo FPDF de PHP.
Emisión de "Certificado Catastral", trazabilidad de impresiones
e historial de emisión legal de los predios.
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
        'views/catastro_certificado_views.xml',
        'reports/certificado_report.xml',
    ],
    'installable': True,
    'application': False,
    'auto_install': False,
}
