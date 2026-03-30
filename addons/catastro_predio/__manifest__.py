# -*- coding: utf-8 -*-
{
    'name': 'Catastro - Predios',
    'version': '17.0.1.0.0',
    'summary': 'Gestión de predios catastrales urbanos y rurales del municipio',
    'description': """
Módulo de Catastro Municipal - Predios
=======================================
Gestión completa de predios catastrales con soporte para:
- Predios urbanos y rurales
- Registro de propietarios y tradición de dominio
- Datos técnicos: colindantes, edificaciones, uso de suelo
- Valores catastrales de terreno y construcción
- Auditoría de cambios via chatter
    """,
    'author': 'Gobierno Autónomo Municipal de Vallegrande',
    'website': 'https://www.vallegrande.gob.bo',
    'category': 'Government/Cadastre',
    'license': 'LGPL-3',
    'depends': [
        'base',
        'contacts',
        'mail',
    ],
    'data': [
        'security/catastro_security.xml',
        'security/ir.model.access.csv',
        'data/catastro_data.xml',
        'views/catastro_colindante_views.xml',
        'views/catastro_predio_views.xml',
        'views/catastro_edificacion_views.xml',
        'views/catastro_menu.xml',
    ],
    'demo': [
        'demo/demo_predios.xml',
    ],
    'installable': True,
    'application': True,
    'auto_install': False,
    'icon': 'static/description/icon.png',
}
