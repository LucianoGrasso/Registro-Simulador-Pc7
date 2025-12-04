<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debriefing de Vuelo - {{ $sesion ? $sesion->alumno->nombre_completo : 'Archivo' }}</title>
    
    <!-- LEAFLET -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { margin: 0; padding: 0; background-color: #1a202c; font-family: system-ui, -apple-system, sans-serif; }
        #map { height: 100vh; width: 100%; z-index: 1; }
        
        .overlay-panel {
            position: absolute; bottom: 30px; left: 20px; z-index: 2000; 
            background: rgba(255, 255, 255, 0.95); padding: 20px; 
            border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            max-width: 320px; backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.5);
        }

        .legend-panel {
            position: absolute; bottom: 30px; right: 20px; z-index: 2000;
            background: rgba(255, 255, 255, 0.9); padding: 10px;
            border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-size: 12px; text-align: center;
        }
        .gradient-bar {
            width: 150px; height: 10px;
            background: linear-gradient(to right, #ef4444, #eab308, #22c55e);
            border-radius: 5px; margin-bottom: 4px;
        }
        .legend-labels { display: flex; justify-content: space-between; color: #4b5563; font-weight: 600; }

        .btn-back {
            position: absolute; top: 20px; right: 20px; z-index: 2000; 
            background-color: #1f2937; color: white; padding: 8px 16px;
            border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.9rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: all 0.2s;
            display: flex; align-items: center; gap: 8px;
        }
        .btn-back:hover { background-color: #374151; transform: translateY(-1px); }
    </style>
</head>
<body>

    <a href="{{ route('sesiones.index') }}" class="btn-back">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver al Historial
    </a>

    <div class="overlay-panel font-sans">
        @if($sesion)
            <div class="border-b border-gray-200 pb-3 mb-3">
                <h2 class="text-xl font-bold text-gray-800 leading-tight">
                    {{ $sesion->alumno->nombre_completo }}
                </h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded">Alumno</span>
                    <p class="text-xs text-gray-500 font-mono">NPI: {{ $sesion->npi }}</p>
                </div>
            </div>
            <div class="mb-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Actividad</p>
                <p class="text-sm text-gray-700 leading-snug">{{ $sesion->actividad }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4 border-b border-gray-200 pb-3">
                <div><span class="block text-xs font-bold text-gray-400">FECHA</span><span class="text-sm font-semibold text-gray-700">{{ $sesion->fecha->format('d/m/Y') }}</span></div>
                <div><span class="block text-xs font-bold text-gray-400">HORA</span><span class="text-sm font-semibold text-gray-700">{{ $sesion->hora_inicio->format('H:i') }}</span></div>
            </div>
        @else
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-2">Vuelo Sin Sesión</h2>
            <p class="text-xs text-gray-500 mb-4 break-all">{{ $archivoJson }}</p>
        @endif
        
        <div>
            <div class="flex justify-between items-end mb-2">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Datos de Vuelo</p>
                <div id="status" class="text-xs text-blue-600 font-semibold animate-pulse">Cargando...</div>
            </div>
            <div class="hidden grid grid-cols-2 gap-2" id="flight-stats">
                <div class="bg-gray-50 p-2 rounded border border-gray-100">
                    <span class="block text-xs text-gray-500">Altitud Máx</span>
                    <span class="font-bold text-gray-800 text-lg"><span id="max-alt">-</span> <span class="text-xs font-normal">ft</span></span>
                </div>
                <div class="bg-gray-50 p-2 rounded border border-gray-100">
                    <span class="block text-xs text-gray-500">Duración</span>
                    <span class="font-bold text-gray-800 text-lg"><span id="duration">-</span> <span class="text-xs font-normal">min</span></span>
                </div>
            </div>
        </div>
    </div>

    <div class="legend-panel font-sans">
        <div class="text-xs font-bold text-gray-500 mb-1 uppercase">Altitud (pies)</div>
        <div class="gradient-bar"></div>
        <div class="legend-labels">
            <span>0</span>
            <span id="mid-legend">1500</span>
            <span id="max-legend">3000+</span>
        </div>
    </div>

    <div id="map"></div>

    <!-- Script para pasar variables de Blade a JS -->
    <script>
        // Inyectamos la URL desde Blade antes de cargar el script principal
        // Usamos asset() para generar la URL completa y json_encode para escaparla correctamente
        window.archivoUrl = {!! json_encode(asset('vuelos/' . $archivoJson)) !!};
    </script>

    <script>
        var map = L.map('map', {zoomControl: false}).setView([-32.949, -71.554], 14);
        L.control.zoom({position: 'topright'}).addTo(map);

        L.tileLayer('/mapas/mapas_naval/{z}/{x}/{y}.png', {
            minZoom: 10, maxZoom: 15, tms: true, attribution: 'Escuela Naval', errorTileUrl: '',
            updateWhenIdle: false, updateWhenZooming: false, keepBuffer: 10, fadeAnimation: false, maxNativeZoom: 15
        }).addTo(map);

        function interpolateColor(color1, color2, factor) {
            var result = color1.slice();
            for (var i = 0; i < 3; i++) {
                result[i] = Math.round(result[i] + factor * (color2[i] - color1[i]));
            }
            return 'rgb(' + result[0] + ',' + result[1] + ',' + result[2] + ')';
        }

        var cRojo = [239, 68, 68]; var cAmarillo = [234, 179, 8]; var cVerde = [34, 197, 94];

        function getGradientColor(alt, minAlt, maxAlt) {
            if (maxAlt === minAlt) return 'rgb(' + cAmarillo.join(',') + ')';
            var pct = (alt - minAlt) / (maxAlt - minAlt);
            if (pct < 0.5) return interpolateColor(cRojo, cAmarillo, pct * 2);
            else return interpolateColor(cAmarillo, cVerde, (pct - 0.5) * 2);
        }

        // Usamos la variable global definida arriba
        fetch(window.archivoUrl)
            .then(res => { 
                if (!res.ok) {
                    throw new Error("Archivo no encontrado: " + window.archivoUrl); 
                }
                return res.json(); 
            })
            .then(data => {
                if (data.length === 0) {
                    document.getElementById('status').innerText = "Sin datos."; return;
                }

                // --- OPTIMIZACIÓN PARA ARCHIVOS GRANDES ---
                // Si hay muchos puntos, calculamos un "paso" para no dibujar todos
                var totalPuntos = data.length;
                var maxPuntosDibujables = 3000; // Límite seguro para navegadores
                var paso = Math.ceil(totalPuntos / maxPuntosDibujables);
                if (paso < 1) paso = 1;

                console.log("Total puntos: " + totalPuntos + ". Dibujando 1 de cada " + paso);

                // Calculamos max/min usando un bucle simple (más seguro que spread operator)
                var minAlt = 0;
                var maxAlt = 0;
                for(var i=0; i<totalPuntos; i++) {
                    if(data[i].alt > maxAlt) maxAlt = data[i].alt;
                }
                if (maxAlt < 1000) maxAlt = 1000;

                document.getElementById('mid-legend').innerText = Math.round(maxAlt / 2);
                document.getElementById('max-legend').innerText = Math.round(maxAlt) + "+";

                var flightLayer = L.featureGroup();
                var allPoints = [];

                // Usamos el 'paso' calculado para saltar puntos y no saturar
                for (var i = 0; i < totalPuntos - paso; i += paso) {
                    var pActual = data[i];
                    var pSiguiente = data[i+paso]; // Conectamos con el siguiente salto

                    if(!pActual || !pSiguiente) continue;

                    var latlngs = [[pActual.lat, pActual.lon], [pSiguiente.lat, pSiguiente.lon]];
                    var colorGradiente = getGradientColor(pActual.alt, minAlt, maxAlt);

                    var linea = L.polyline(latlngs, {
                        color: colorGradiente, weight: 6, opacity: 1, smoothFactor: 1
                    });

                    // Popup simplificado
                    var contenidoPopup = `
                        <div style="text-align: center;">
                            <strong style="color: #4b5563;">Altitud</strong><br>
                            <span style="font-size: 14px; font-weight: bold;">${Math.round(pActual.alt)} ft</span>
                        </div>
                    `;
                    linea.bindPopup(contenidoPopup, {closeButton: false});
                    
                    linea.addTo(flightLayer);
                    allPoints.push([pActual.lat, pActual.lon]);
                }

                flightLayer.addTo(map);

                var bounds = L.polyline(allPoints).getBounds();
                map.fitBounds(bounds, { maxZoom: 15, padding: [50, 50] });

                var pInicio = data[0]; var pFin = data[totalPuntos-1];
                L.circleMarker([pInicio.lat, pInicio.lon], {color: 'white', fillColor: 'black', fillOpacity: 1, radius: 6}).addTo(map).bindPopup("<b>Inicio</b>");
                L.circleMarker([pFin.lat, pFin.lon], {color: 'white', fillColor: 'blue', fillOpacity: 1, radius: 6}).addTo(map).bindPopup("<b>Fin</b>");

                document.getElementById('status').innerText = "Visualización lista";
                document.getElementById('status').className = "text-xs text-green-600 font-bold uppercase";
                document.getElementById('status').classList.remove('animate-pulse');
                
                document.getElementById('flight-stats').classList.remove('hidden');
                document.getElementById('max-alt').innerText = Math.round(maxAlt);
                
                var duracion = 0;
                // Intentamos calcular duración, protegiéndonos si falta el timestamp
                if(data[0].ts && data[totalPuntos-1].ts) {
                    // Si el timestamp es muy grande (X-Plane a veces usa segundos desde inicio simulador)
                    var t1 = data[0].ts;
                    var t2 = data[totalPuntos-1].ts;
                    if(t2 > t1) {
                        duracion = ((t2 - t1) / 60).toFixed(1);
                    }
                }
                document.getElementById('duration').innerText = duracion;
            })
            .catch(err => { 
                console.error(err); 
                document.getElementById('status').innerText = "Error visualización."; 
                document.getElementById('status').className = "text-xs text-red-600 font-bold";
            });
    </script>
</body>
</html>