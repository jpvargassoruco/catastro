# Informe Técnico de Migración y Desarrollo
**Proyecto:** Modernización del Sistema Catastral Municipal (SIICAT a Odoo 17 LTS)
**Cliente/Institución:** Gobierno Autónomo Municipal de Vallegrande
**Fecha de Elaboración:** Marzo de 2026

---

## 1. Resumen Ejecutivo
El presente informe documenta el proceso íntegro de reingeniería, refactorización y despliegue del sistema catastral heredado (antiguamente desarrollado en PHP nativo plano, FPDF y consultas SQL quemadas en el código) hacia el ERP de nivel empresarial **Odoo 17 LTS**.

El objetivo principal fue estandarizar, asegurar e integrar los procesos tributarios y geográficos del municipio bajo una sola arquitectura (Python, PostgreSQL, QWeb, OWL Framework), eliminando vulnerabilidades web, código de espagueti in-mantenible y desacoples financieros.

## 2. Infraestructura y Arquitectura de Despliegue
Se estableció una base sólida basada en Infraestructura Celular (*Containers*):
- **Motor de Base de Datos:** PostgreSQL 16 (reemplazando al antiguo clúster local, preparado para soporte PostGIS en caso de requerirse almacenamiento binario nativo absoluto).
- **Orquestación:** Docker Engine y `docker-compose`.
- **Enrutamiento Inverso (Reverse Proxy):** Traefik v2+ configurado para rutear peticiones HTTP internas, habilitar Websockets para los chats en tiempo real de Odoo y proveer una ruta inmaculada `http://catastro.local`.
- **Pipelines y CI/CD:** Se consolidaron *GitHub Actions* (`build-push.yml`) para compilar automáticamente la imagen del backend y subirla al *Github Container Registry* remoto de manera encriptada.

## 3. Módulos Core Programados (`addons/`)
Se fragmentó la lógica monolítica PHP hacia una estructura modular jerárquica con dependencias limpias:

### 3.1. Núcleo Maestro (`catastro_predio`)
Se programaron modelos altamente tipados que agrupan y extienden las abstracciones de los predios.
- Reemplazo orgánico de un modelo plano a un sistema de herencia con la tabla `res.partner` (Módulo Base de Contactos Odoo) para administrar los **Propietarios, Contribuyentes y Jurídicos** utilizando el estándar oficial CRM del ERP.

### 3.2. Motor Tributario y Facturación (`catastro_impuestos`)
- **Desarrollo clave:** Se decodificó e interceptó el script procesal `siicat_impuestos_calc.php`. Las fórmulas ahora residen en métodos de objeto `@api.depends` con Python.
- **Contabilidad Nativa:** En lugar de aislar recibos en una base de datos propia, **se inyectó una integración pura con `account.move`** (Módulo de Contabilidad Oficial). Cada liquidación generada dispara un comprobante contable que entra al balance del libro mayor municipal.

### 3.3. Certificados y Reportes Topográficos (`catastro_certificados` e `catastro_informes`)
- Se erradicó por completo el entorno `FPDF` que requería programar PDFs pintando coordenadas (X, Y).
- Se redactaron **Plantillas QWeb Interactivos**, donde el motor Odoo rutea el HTML/Bootstrap 5 dinámicamente y expulsa reportes de alta fidelidad:
  1. *Boleta de Pago de Impuestos*
  2. *Certificado Catastral Oficial*
  3. *Informe de Empadronamiento In-Situ*
  4. *Certificación Cota de Nivel Urbana*

### 3.4. Trámites, Legal y Cartografía Mapeada (`catastro_transferencia`, `catastro_gravamen`, `catastro_mapa`)
- **Auditoría Transaccional:** Cada proceso lleva un historial (*Chatter/mail.thread*) para saber qué usuario del municipio firmó, anotó o paralizó el registro.
- **Reemplazo de MapServer:** Para abolir dependencias satélite antiguas en el renderizado topográfico, se programó un Componente JavaScript moderno **(OWL)** nativo, inyectando la librería `Leaflet.js` y `OpenStreetMap` en el formulario visual Odoo. Si el municipio sube un Polígono GeoJSON referencial, el ERP despliega, dibuja y centra instantáneamente la parcela en el mapa satelital HTML.

## 4. Tubería Migratoria ETL (Sincronización de Datos)
El choque entre la vieja DB tabular y el Odoo moderno resolvía un abismo técnico. 
Se programó y homologó el script `scripts/migrate_siicat_to_odoo.py` para ejecutarse en terminal. Este script:
1. Conecta al clúster Postgres original vía conector plano (`psycopg2`).
2. Recoleta el volumen heredero crudo y evalúa posibles valores nulos/conflictos.
3. Se enlaza al servidor interno recién levantado del ERP bajo la interfaz **XML-RPC** inyectando transaccionalmente cada registro (e.g. `res.partner`), preservando la limpieza semántica.

---

### Conclusión y Disponibilidad
La plataforma está compilada y corriendo actualmente. Su lanzamiento simboliza un hito de interoperabilidad corporativa. El Gobierno Municipal ahora cuenta con un software donde Recursos Humanos, Compras, Facturación, Tesorería y, finalmente, **Lógica Catastral de Libreta Avanzada**, conviven unificadamente y garantizan perdurabilidad a largo plazo.
