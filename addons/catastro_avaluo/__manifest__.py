# -*- coding: utf-8 -*-
{
    'name': 'Catastro - Avalúo Catastral',
    'version': '17.0.1.0.0',
    'summary': 'Cálculo del valor catastral de predios por gestión',
    'description': """
Módulo de Catastro Municipal - Avalúo Catastral
================================================
- Tablas de valores unitarios Bs/m² por zona, uso de suelo y gestión
- Avalúo por predio: valor terreno + valor construcción
- Flujo de aprobación: Calculado → Aprobado → Vigente
- Wizard de recálculo masivo por zona/gestión
- Integración con catastro_predio (valor catastral vigente en la ficha)
    """,
    'author': 'Gobierno Autónomo Municipal de Vallegrande',
    'website': 'https://www.vallegrande.gob.bo',
    'category': 'Government/Cadastre',
    'license': 'LGPL-3',
    'depends': [
        'catastro_predio',
        'mail',
    ],
    'data': [
        'security/ir.model.access.csv',
        'data/ir_sequence_data.xml',
        'views/catastro_tabla_valor_views.xml',
        'views/catastro_avaluo_views.xml',
        'views/catastro_predio_avaluo_views.xml',
        'wizard/wizard_recalculo_masivo_views.xml',
        'views/menu_views.xml',
    ],
    'demo': [
        'demo/demo_tabla_valor.xml',
        'demo/demo_avaluos.xml',
    ],
    'installable': True,
    'application': False,
    'auto_install': False,
}
