# -*- coding: utf-8 -*-
{
    'name': 'Catastro - Transferencias de Dominio',
    'version': '17.0.1.0.0',
    'summary': 'Gestión de transferencias temporales y permanentes de predios',
    'description': """
Módulo de Catastro Municipal - Transferencias
=============================================
Gestiona los cambios de derecho propietario (tradición de dominio),
emitiendo preliquidaciones de impuesto a la transferencia y
asegurando consistencia jurídica del predio.
    """,
    'author': 'Gobierno Autónomo Municipal de Vallegrande',
    'website': 'https://github.com/Pothoko/catastro_01',
    'category': 'Government/Cadastre',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'catastro_predio',
        'catastro_impuestos',
        'mail',
    ],
    'data': [
        'security/ir.model.access.csv',
        'views/catastro_transferencia_views.xml',
    ],
    'installable': True,
    'application': False,
    'auto_install': False,
}
