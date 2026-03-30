# -*- coding: utf-8 -*-
from odoo import models, fields


class CatastroColindante(models.Model):
    _name = 'catastro.colindante'
    _description = 'Colindante de Predio Catastral'
    _order = 'predio_id, orientacion'

    predio_id = fields.Many2one(
        comodel_name='catastro.predio',
        string='Predio',
        required=True,
        ondelete='cascade',
        index=True,
    )
    orientacion = fields.Selection(
        selection=[
            ('norte', 'Norte'),
            ('sur', 'Sur'),
            ('este', 'Este'),
            ('oeste', 'Oeste'),
            ('noreste', 'Noreste'),
            ('noroeste', 'Noroeste'),
            ('sureste', 'Sureste'),
            ('suroeste', 'Suroeste'),
        ],
        string='Orientación',
        required=True,
    )
    descripcion = fields.Char(
        string='Descripción del Colindante',
        required=True,
        help='Nombre del propietario colindante, calle, río, etc.',
    )
    medida = fields.Float(
        string='Medida (m)',
        digits=(8, 2),
        help='Longitud del lindero en metros',
    )
    referencia = fields.Char(
        string='Referencia Catastral',
        help='Clave catastral del predio colindante si está registrado',
    )
