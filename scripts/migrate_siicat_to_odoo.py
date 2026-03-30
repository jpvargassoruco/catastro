#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Migrador ETL: SIICAT Legacy (PHP/PostgreSQL) -> Odoo 17
========================================================
Fase 9: Script maestro de migración de datos. Conecta directamente a la 
antigua base de datos `vallegrande` en plano y los inyecta validando 
tipos de datos mediante la API XML-RPC de Odoo.
"""

import xmlrpc.client
import psycopg2
import logging
from datetime import datetime

# --- Configuración Odoo 17 (DB Destino) ---
ODOO_URL = 'http://localhost:8069'
ODOO_DB = 'catastro_vallegrande'  # Nombre que asignes a tu DB
ODOO_USER = 'admin'
ODOO_PASS = 'catastro_admin_2026'

# --- Configuración PostgreSQL Legacy SIICAT (DB Origen) ---
PG_HOST = 'localhost'
PG_DB = 'vallegrande'
PG_USER = 'postgres'
PG_PASS = 'odoo_secret'

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s: %(message)s')

def migrate_contribuyentes():
    """ Migración de personas y empresas hacia res.partner """
    try:
        common = xmlrpc.client.ServerProxy(f'{ODOO_URL}/xmlrpc/2/common')
        uid = common.authenticate(ODOO_DB, ODOO_USER, ODOO_PASS, {})
        if not uid:
            logging.error("Autenticación Odoo Fallida")
            return
        
        models = xmlrpc.client.ServerProxy(f'{ODOO_URL}/xmlrpc/2/object')
        
        conn = psycopg2.connect(host=PG_HOST, dbname=PG_DB, user=PG_USER, password=PG_PASS)
        cur = conn.cursor()

        logging.info("Iniciando migración de la tabla contribuyentes...")
        cur.execute("SELECT id_contrib, nombres, apellidos, carnet, direccion, civil FROM contribuyentes")
        
        migrados = 0
        for row in cur.fetchall():
            id_legacy = row[0]
            nombres = row[1] or ''
            apellidos = row[2] or ''
            
            # Mapeo a modelo de Odoo
            partner_vals = {
                'name': f"{nombres} {apellidos}".strip(),
                'vat': row[3],          # NIT o Carnet
                'street': row[4],       # Dirección fiscal
                'comment': f"Importado de SIICAT ID original: {id_legacy} - Estado Civil: {row[5]}",
                'is_company': False,
            }
            
            try:
                new_id = models.execute_kw(ODOO_DB, uid, ODOO_PASS, 'res.partner', 'create', [partner_vals])
                migrados += 1
            except Exception as e:
                logging.warning(f"Error migrando ID {id_legacy}: {str(e)}")

        logging.info(f"Éxito: {migrados} contribuyentes importados al módulo base de Odoo.")
        
        cur.close()
        conn.close()

    except Exception as e:
        logging.error(f"Falla fatal en script de migración: {str(e)}")

if __name__ == '__main__':
    logging.info("ETL SIICAT -> ODOO V17 INICIADO")
    # Para ejecutar: python3 scripts/migrate_siicat_to_odoo.py
    # migrate_contribuyentes() # Descomentar cuando la DB viva esté adjunta.
