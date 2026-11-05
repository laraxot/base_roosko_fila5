<x-mobile::layout>
    <x-slot:title>Mappa — FixCity</x-slot:title>
    <x-ui.marketing.page-header
        title="🗺️ Mappa Segnalazioni"
        description="Visualizza tutte le segnalazioni civiche in tempo reale"
    />

    <style>
        #map { height: 600px; width: 100%; border-radius: 12px; z-index: 1; }
        .leaflet-popup-content-wrapper { border-radius: 12px; }
        .leaflet-popup-content { margin: 10px 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .leaflet-popup-content h3 { margin: 0 0 6px 0; font-size: 14px; color: #1976D2; }
        .leaflet-popup-content p { margin: 2px 0; font-size: 12px; color: #555; }
        .leaflet-popup-content .status-open { color: #f59e0b; font-weight: 600; }
        .leaflet-popup-content .status-progress { color: #3b82f6; font-weight: 600; }
        .leaflet-popup-content .status-resolved { color: #10b981; font-weight: 600; }
        .leaflet-popup-content .status-closed { color: #6b7280; font-weight: 600; }
        .leaflet-control-zoom { border-radius: 8px !important; }
        .leaflet-control-zoom a { border-radius: 8px !important; }
        .legend { background: white; padding: 8px 12px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); font-size: 12px; line-height: 1.6; }
        .legend i { width: 12px; height: 12px; float: left; margin-right: 6px; border-radius: 50%; }
    </style>

    <div id="map" role="application" aria-label="Mappa interattiva delle segnalazioni civiche"></div>

    <div class="legend" id="legend" style="margin-top: 12px;">
        <strong>Stati</strong><br>
        <i style="background:#f59e0b;"></i> Aperto &nbsp;
        <i style="background:#3b82f6;"></i> In corso &nbsp;
        <i style="background:#10b981;"></i> Risoluto &nbsp;
        <i style="background:#6b7280;"></i> Chiuso
    </div>

    <div style="margin-top: 16px; text-align: center;">
        <a href="/data/tickets.json" style="color: #1976D2;">📄 Scarica GeoJSON completo</a>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <script>
        // Accessibility: announce map load to screen readers
        document.addEventListener('DOMContentLoaded', function() {
            var mapEl = document.getElementById('map');
            if (mapEl) {
                mapEl.setAttribute('aria-live', 'polite');
                mapEl.setAttribute('aria-label', 'Mappa interattiva delle segnalazioni civiche di FixCity');
            }
        });

        // Initialize map
        var map = L.map('map', {
            zoomControl: true,
            attributionControl: true
        }).setView([45.5601, 12.241], 13);

        // Bootstrap Italia-inspired tile layer (using CartoDB positron)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        // Status color mapping
        var statusColors = {
            'open': '#f59e0b',
            'in_progress': '#3b82f6',
            'resolved': '#10b981',
            'closed': '#6b7280'
        };

        // Fetch GeoJSON and render markers
        fetch('/data/tickets.json')
            .then(function(response) {
                if (!response.ok) throw new Error('GeoJSON non disponibile');
                return response.json();
            })
            .then(function(geojson) {
                if (!geojson || !geojson.features || geojson.features.length === 0) {
                    document.getElementById('map').innerHTML = '<div style="padding:40px;text-align:center;color:#666;">Nessuna segnalazione disponibile</div>';
                    return;
                }

                L.geoJSON(geojson, {
                    pointToLayer: function(feature, latlng) {
                        var status = feature.properties.status?.value || 'open';
                        var color = statusColors[status] || '#f59e0b';
                        return L.circleMarker(latlng, {
                            radius: 8,
                            fillColor: color,
                            color: '#fff',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.85
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        var props = feature.properties || {};
                        // Titolo, indirizzo e citta' li scrive il cittadino: nel popup entrano come TESTO, mai come HTML (audit DCP-181, C-2).
                        var esc = function (value) {
                            return String(value == null ? '' : value).replace(/[&<>"']/g, function (c) {
                                return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c];
                            });
                        };
                        // Solo percorsi dello stesso sito o http(s): niente `javascript:` nell'href.
                        var safeUrl = function (value) {
                            return /^(\/|https?:\/\/)/i.test(String(value || '')) ? esc(value) : '#';
                        };
                        var statusClass = 'status-' + esc(props.status?.value || 'open').replace(/[^a-z0-9_-]/gi, '');
                        var popupContent = '<h3>' + esc(props.title || 'Segnalazione') + '</h3>' +
                            '<p><strong>Tipo:</strong> ' + esc(props.type?.label || '-') + '</p>' +
                            '<p><strong>Città:</strong> ' + esc(props.city || '-') + '</p>' +
                            '<p><strong>Indirizzo:</strong> ' + esc(props.address || '-') + '</p>' +
                            '<p><strong>Stato:</strong> <span class="' + statusClass + '">' + esc(props.status?.label || '-') + '</span></p>' +
                            '<p><a href="' + safeUrl(props.detail_url) + '">Dettaglio →</a></p>';
                        layer.bindPopup(popupContent);
                    }
                }).addTo(map);

                // Accessibility: announce number of markers
                var mapEl = document.getElementById('map');
                if (mapEl) {
                    mapEl.setAttribute('aria-label', 'Mappa con ' + geojson.features.length + ' segnalazioni civiche');
                }

                // Fit bounds to show all markers
                map.fitBounds(L.geoJSON(geojson).getBounds(), { padding: [50, 50] });
            })
            .catch(function(err) {
                console.error('Errore caricamento GeoJSON:', err);
                document.getElementById('map').innerHTML = '<div style="padding:40px;text-align:center;color:#d32f2f;">Errore nel caricamento della mappa. <a href="/data/tickets.json">Scarica GeoJSON</a></div>';
            });
    </script>
</x-mobile::layout>