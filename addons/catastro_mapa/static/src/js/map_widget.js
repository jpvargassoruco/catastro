/** @odoo-module **/

import { registry } from "@web/core/registry";
import { standardFieldProps } from "@web/views/fields/standard_field_props";
import { Component, onMounted, onWillUnmount, useRef } from "@odoo/owl";

export class CatastroMapWidget extends Component {
    static template = "catastro_mapa.MapWidget";
    static props = {
        ...standardFieldProps,
    };

    setup() {
        this.mapContainer = useRef("mapContainer");
        this.map = null;

        onMounted(() => {
            // Inicialización del visor cartográfico dinámico
            if (typeof L !== 'undefined') {
                this.initMap();
            } else {
                console.warn("Librería cartográfica Leaflet no disponible en este entorno.");
            }
        });

        onWillUnmount(() => {
            if (this.map) {
                this.map.remove();
            }
        });
    }

    initMap() {
        // Coordenadas céntricas referencia: Vallegrande, Santa Cruz, Bolivia
        this.map = L.map(this.mapContainer.el).setView([-18.4897, -64.1065], 15);
        
        // Capa satelital pública como base
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors | Catastro Vallegrande'
        }).addTo(this.map);

        const capa_raw = this.props.record.data.coordenadas_json;
        if (capa_raw) {
            try {
                const geojsonData = JSON.parse(capa_raw);
                const layer = L.geoJSON(geojsonData, {
                    style: function (feature) {
                        return {color: feature.properties.color || "#ff7800", weight: 2, opacity: 0.8};
                    }
                }).addTo(this.map);
                
                // Centrar dinámicamente el visor al polígono del predio (si aplica)
                this.map.fitBounds(layer.getBounds());
            } catch (e) {
                console.error("El formato GeoJSON alojado en PostGIS/Odoo no es válido visualmente", e);
            }
        }
    }
}

// Inyección del widget en el ecosistema OWL de Odoo 17
registry.category("fields").add("catastro_leaflet_map", CatastroMapWidget);
