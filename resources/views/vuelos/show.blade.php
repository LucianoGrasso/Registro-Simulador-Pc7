<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debriefing de Vuelo - Armada de Chile</title>
    
    <script src="https://cdn.tailwindcss.com"></script> 

    <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}" />
    <script src="{{ asset('leaflet/leaflet.js') }}"></script>

    <style>
        body { margin: 0; padding: 0; background-color: #1a202c; }
        #map { height: 100vh; width: 100%; z-index: 1; }
        
        /* CAJA DE INFORMACIÓN (Ahora abajo) */
        .overlay-panel {
            position: absolute; 
            bottom: 30px; /* <--- CAMBIO: Antes era top: 20px */
            left: 20px; 
            z-index: 2000; /* <--- Aumentamos Z-Index para asegurar que se vea */
            background: rgba(255, 255, 255, 0.95);
            padding: 15px; border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
            max-width: 300px;
        }

        /* BOTÓN VOLVER */
        .btn-back {
            position: absolute; 
            top: 20px; 
            right: 20px; 
            z-index: 2000; /* <--- SUPER IMPORTANTE: Para que el mapa no bloquee el clic */
            background: #2d3748; 
            color: white; 
            padding: 10px 20px;
            border-radius: 5px; 
            text-decoration: none; 
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.5);
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-back:hover { background: #4a5568; }
    </style>
</head>
<body>

    <a href="{{ route('vuelos.index') }}" class="btn-back">← Volver al Historial</a>

    <div class="overlay-panel">
        
        @if($sesion)
            <div class="border-b pb-3 mb-3">
                <h2 class="text-lg font-bold text-gray-800 leading-tight">
                    {{ $sesion->alumno->nombre_completo }}
                </h2>
                <p class="text-xs text-gray-500 font-mono mt-1">NPI: {{ $sesion->npi }}</p>
            </div>

            <div class="mb-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Actividad Realizada</p>
                <p class="text-sm text-gray-700 mt-1 leading-snug">
                    {{ $sesion->actividad }}
                </p>
            </div>

            <div class="flex justify-between mb-4 text-xs text-gray-500 border-b pb-3">
                <div>
                    <span class="block font-bold text-gray-400">FECHA</span>
                    {{ $sesion->fecha->format('d/m/Y') }}
                </div>
                <div>
                    <span class="block font-bold text-gray-400">HORA</span>
                    {{ $sesion->hora_inicio->format('H:i') }}
                </div>
                <div>
                    <span class="block font-bold text-gray-400">DURACIÓN</span>
                    {{ $sesion->duracion_minutos ?? '-' }} min
                </div>
            </div>
        @else
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-2">Archivo Histórico</h2>
            <p class="text-xs text-gray-500 mb-4">{{ $archivoJson }}</p>
        @endif
        
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Telemetría</p>
            
            <div id="status" class="text-sm text-blue-600 font-semibold mb-2">Cargando...</div>

            <div class="hidden grid grid-cols-2 gap-2" id="flight-stats">
                <div class="bg-gray-50 p-2 rounded">
                    <span class="block text-xs text-gray-500">Techo Máx</span>
                    <span class="font-bold text-gray-800"><span id="max-alt">-</span> ft</span>
                </div>
                <div class="bg-gray-50 p-2 rounded">
                    <span class="block text-xs text-gray-500">Tiempo Vuelo</span>
                    <span class="font-bold text-gray-800"><span id="duration">-</span> min</span>
                </div>
            </div>
        </div>
    </div>

    <div id="map"></div>

    <script>
        // 1. Configuración del Mapa
        // Coordenadas Base: Concón (SCVM)
        var map = L.map('map').setView([-32.949, -71.554], 14);

        // CAPA DE MAPA (Tiles)
        // MODO HÍBRIDO: Intenta cargar local, si no hay, usa online (para cuando desarrollas en casa)
        
        // --- OPCIÓN A: ONLINE (Para probar hoy en tu casa) ---
        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     maxZoom: 19,
        //     attribution: '© OpenStreetMap'
        // }).addTo(map);

        // --- OPCIÓN B: OFFLINE (Para el simulador real) ---
        // Descomenta esto cuando tengas la carpeta public/mapas
        
        L.tileLayer('/mapas/mapas_naval/{z}/{x}/{y}.png', {
            minZoom: 12,
            maxZoom: 16,
            tms: true,
            attribution: 'Escuela Naval',
            errorTileUrl: '', // Opcional: imagen si falta un tile

            updateWhenIdle: false,      // Carga INMEDIATAMENTE al moverte, no espera a que frenes
            updateWhenZooming: false,   // Carga mientras haces zoom
            keepBuffer: 12,             // Carga muchos más cuadros alrededor "por si acaso" (usa más RAM, pero es más fluido)
            fadeAnimation: false,       // Quita el efecto de "aparecer suave", hace que la imagen aparezca de golpe (se siente más rápido)
            maxNativeZoom: 16           // Asegura que sepa cuál es el tope real
        }).addTo(map);
        

        // Función para mezclar dos colores (Inicio -> Fin) según un porcentaje (0.0 a 1.0)
        function interpolateColor(color1, color2, factor) {
            var result = color1.slice();
            for (var i = 0; i < 3; i++) {
                result[i] = Math.round(result[i] + factor * (color2[i] - color1[i]));
            }
            return 'rgb(' + result[0] + ',' + result[1] + ',' + result[2] + ')';
        }

        // Función principal: Devuelve el color según la altitud relativa
        // Define los colores RGB aquí: [Rojo, Verde, Azul]
        var cRojo = [239, 68, 68];   // Tailwind red-500 (Bajo)
        var cAmarillo = [234, 179, 8]; // Tailwind yellow-500 (Medio)
        var cVerde = [34, 197, 94];  // Tailwind green-500 (Alto)

        function getGradientColor(alt, minAlt, maxAlt) {
            // Proteccion contra division por cero si el vuelo fue plano
            if (maxAlt === minAlt) return 'rgb(' + cAmarillo.join(',') + ')';

            // 1. Calcular porcentaje de altitud (0.0 a 1.0)
            var pct = (alt - minAlt) / (maxAlt - minAlt);

            // 2. Interpolar (Mezclar) colores
            if (pct < 0.5) {
                // Mitad inferior: Mezclar Rojo -> Amarillo
                // Re-normalizamos pct para que vaya de 0.0 a 1.0 en esta mitad
                var subPct = pct * 2; 
                return interpolateColor(cRojo, cAmarillo, subPct);
            } else {
                // Mitad superior: Mezclar Amarillo -> Verde
                var subPct = (pct - 0.5) * 2;
                return interpolateColor(cAmarillo, cVerde, subPct);
            }
        }


        // --- CARGA Y DIBUJO DE DATOS ---
        var archivoUrl = "{{ asset('vuelos/' . $archivoJson) }}";

        fetch(archivoUrl)
            .then(res => { if (!res.ok) throw new Error("Archivo no encontrado"); return res.json(); })
            .then(data => {
                if (data.length === 0) {
                    document.getElementById('status').innerText = "Sin datos."; return;
                }

                // 1. PRE-ANÁLISIS: Encontrar Altitud Mínima y Máxima del vuelo
                var alts = data.map(p => p.alt);
                var minAlt = Math.min(...alts);
                var maxAlt = Math.max(...alts);
                // Ajuste para que el "rojo" puro sea cerca del suelo, no solo el punto más bajo del vuelo
                minAlt = Math.max(0, minAlt - 100); 

                var flightLayer = L.featureGroup();
                var allPoints = [];

                // 2. DIBUJO SEGMENTADO CON GRADIENTE
                for (var i = 0; i < data.length - 1; i++) {
                    var pActual = data[i];
                    var pSiguiente = data[i+1];

                    var latlngs = [[pActual.lat, pActual.lon], [pSiguiente.lat, pSiguiente.lon]];
                    
                    // Calculamos el color exacto para la altitud de este punto
                    var colorGradiente = getGradientColor(pActual.alt, minAlt, maxAlt);

                    L.polyline(latlngs, {
                        color: colorGradiente, weight: 5, opacity: 1, smoothFactor: 0
                    }).addTo(flightLayer);

                    allPoints.push([pActual.lat, pActual.lon]);
                }

                flightLayer.addTo(map);

                // 3. Ajustes finales de UI
                var bounds = L.polyline(allPoints).getBounds();
                map.fitBounds(bounds, { maxZoom: 15, padding: [50, 50] });

                var pInicio = data[0]; var pFin = data[data.length-1];
                L.circleMarker([pInicio.lat, pInicio.lon], {color: 'white', fillColor: 'black', fillOpacity: 1, radius: 6}).addTo(map).bindPopup("Inicio: " + Math.round(pInicio.alt) + "ft");
                L.circleMarker([pFin.lat, pFin.lon], {color: 'white', fillColor: 'blue', fillOpacity: 1, radius: 6}).addTo(map).bindPopup("Fin: " + Math.round(pFin.alt) + "ft");

                document.getElementById('status').innerText = "Visualización completada";
                document.getElementById('status').className = "text-sm text-green-600 font-semibold";
                document.getElementById('flight-stats').classList.remove('hidden');
                document.getElementById('max-alt').innerText = Math.round(maxAlt);
                var durationMin = ((data[data.length-1].ts - data[0].ts) / 60).toFixed(1);
                document.getElementById('duration').innerText = durationMin;
            })
            .catch(err => { console.error(err); document.getElementById('status').innerText = "Error cargando."; });
    </script>
</body>
</html>