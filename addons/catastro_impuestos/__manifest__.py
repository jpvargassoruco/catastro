# -*- coding: utf-8 -*-
{
    'name': 'Catastro - Impuestos Prediales',
    'version': '17.0.1.0.0',
    'summary': 'Gestión de Impuestos Prediales y emisión de boletas (Fase 5)',
    'description': """
Módulo de Catastro Municipal - Impuestos
========================================
Permite calcular tasas de impuestos configurables por año,
generar pre-liquidaciones y boletas de pago integrándose con Odoo Accounting.
    """,
    'author': 'Gobierno Autónomo Municipal de Vallegrande',
    'website': 'https://github.com/Pothoko/catastro_01',
    'category': 'Government/Cadastre',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'catastro_predio',
        'account',
    ],
    'data': [
        'security/ir.model.access.csv',
        'views/catastro_impuesto_views.xml',
        'reports/boleta_pago_report.xml',
    ],
    'installable': True,
    'application': False,
    'auto_install': False,
}
