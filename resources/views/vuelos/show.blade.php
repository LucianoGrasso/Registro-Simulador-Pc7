<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debriefing - {{ $sesion ? $sesion->alumno->nombre_completo : 'Vuelo' }}</title>
    
    <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}" />
    <script src="{{ asset('leaflet/leaflet.js') }}"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { margin: 0; padding: 0; background-color: #0f172a; font-family: system-ui, -apple-system, sans-serif; overflow: hidden; }
        #map { height: 100vh; width: 100%; z-index: 1; }
        
        /* PANELES (Sin cambios, solo ocultamos scrollbars por si acaso) */
        .info-panel {
            position: absolute; bottom: 20px; left: 20px; z-index: 2000; 
            background: rgba(255, 255, 255, 0.95); padding: 20px; 
            border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 300px; backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,1);
        }

        .control-panel {
            position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 2000;
            background: rgba(255, 255, 255, 0.95); padding: 15px 25px;
            border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            width: 90%; max-width: 900px; border: 1px solid rgba(209, 213, 219, 0.5);
            backdrop-filter: blur(8px); color: #1f2937;
        }

        .btn-back {
            position: absolute; top: 20px; left: 20px; z-index: 2000; 
            background-color: #1f2937; color: white; padding: 8px 16px;
            border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: all 0.2s;
            display: flex; align-items: center; gap: 8px;
        }
        .btn-back:hover { background-color: #374151; transform: translateY(-1px); }

        input[type=range] { -webkit-appearance: none; width: 100%; background: transparent; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; height: 16px; width: 16px;
            border-radius: 50%; background: #2563eb; cursor: pointer;
            margin-top: -6px; box-shadow: 0 0 5px rgba(37, 99, 235, 0.5);
        }
        input[type=range]::-webkit-slider-runnable-track {
            width: 100%; height: 4px; cursor: pointer; background: #e5e7eb; border-radius: 2px;
        }
        
        /* --- AQUÍ ESTÁ LA MAGIA DEL AVIÓN --- */
        #planeImg {
            /* Sombra fuerte para efecto 3D sobre el mapa */
            filter: drop-shadow(0px 10px 10px rgba(0,0,0,0.6));
            /* Transición suave para rotación y cambio de tamaño */
            transition: transform 0.1s linear, width 0.3s ease, height 0.3s ease;
            /* Renderizado nítido */
            image-rendering: -webkit-optimize-contrast;
        }
    </style>
</head>
<body>

    <a href="{{ route('vuelos.index') }}" class="btn-back">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver
    </a>

    <div class="info-panel font-sans">
        @if($sesion)
            <div class="border-b border-gray-200 pb-3 mb-3">
                <h2 class="text-lg font-bold text-gray-800 leading-tight">
                    {{ $sesion->alumno->nombre_completo }}
                </h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded">Alumno</span>
                    <p class="text-xs text-gray-500 font-mono">NPI: {{ $sesion->npi }}</p>
                </div>
            </div>
            <div class="mb-3">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Actividad</p>
                <p class="text-sm text-gray-700 leading-snug">{{ $sesion->actividad ?? 'Vuelo de Instrucción' }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-3 border-b border-gray-200 pb-3">
                <div><span class="block text-xs font-bold text-gray-400">FECHA</span><span class="text-sm font-semibold text-gray-700">{{ $sesion->fecha->format('d/m/Y') }}</span></div>
                <div><span class="block text-xs font-bold text-gray-400">HORA</span><span class="text-sm font-semibold text-gray-700">{{ $sesion->hora_inicio->format('H:i') }}</span></div>
            </div>
        @else
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-2">Vuelo Sin Sesión</h2>
            <p class="text-xs text-gray-500 mb-4 break-all">{{ $archivoNombre }}</p>
        @endif
        
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Resumen</p>
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-gray-50 p-2 rounded border border-gray-100">
                    <span class="block text-xs text-gray-500">Altitud Máx</span>
                    <span class="font-bold text-gray-800 text-base"><span id="max-alt">-</span> <span class="text-xs font-normal">ft</span></span>
                </div>
                <div class="bg-gray-50 p-2 rounded border border-gray-100">
                    <span class="block text-xs text-gray-500">Duración</span>
                    <span class="font-bold text-gray-800 text-base"><span id="duration">-</span> <span class="text-xs font-normal">min</span></span>
                </div>
            </div>
        </div>
    </div>

    <div class="control-panel font-sans">
        <div class="mb-2 flex items-center gap-4">
            <span class="text-xs text-gray-500 font-mono w-12" id="currentTime">00:00</span>
            <input type="range" id="timeSlider" min="0" max="100" value="0" step="1">
            <span class="text-xs text-gray-500 font-mono w-12" id="totalTime">--:--</span>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <button id="btnPlay" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full shadow transition hover:scale-105">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                </button>
                <button id="btnPause" class="hidden bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-full shadow transition hover:scale-105">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                </button>
                
                <select id="speedBtn" class="bg-gray-50 border border-gray-300 text-gray-700 text-xs rounded px-2 py-1 focus:outline-none cursor-pointer hover:bg-gray-100">
                    <option value="200">0.5x</option>
                    <option value="100" selected>1x</option>
                    <option value="50">2x</option>
                    <option value="10">10x</option>
                </select>
            </div>

            <div class="flex gap-6 text-center items-center">
                <div class="min-w-[60px]">
                    <span class="block text-[10px] text-gray-500 uppercase font-bold tracking-wider">Altitud</span>
                    <span class="text-lg font-bold text-blue-600" id="liveAlt">0</span>
                    <span class="text-xs text-gray-500">ft</span>
                </div>
                <div class="min-w-[60px]">
                    <span class="block text-[10px] text-gray-500 uppercase font-bold tracking-wider">Velocidad</span>
                    <span class="text-lg font-bold text-green-600" id="liveSpd">0</span>
                    <span class="text-xs text-gray-500">kts</span>
                </div>
                <div class="min-w-[60px]">
                    <span class="block text-[10px] text-gray-500 uppercase font-bold tracking-wider">Rumbo</span>
                    <span class="text-lg font-bold text-yellow-600" id="liveHdg">0</span>
                    <span class="text-xs text-gray-500">°</span>
                </div>

                <div class="flex flex-col justify-center ml-4 pl-6 border-l border-gray-200">
                    <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-1 text-left">Escala Altitud</span>
                    <div style="width: 120px; height: 8px; background: linear-gradient(to right, #ef4444, #eab308, #22c55e); border-radius: 4px;"></div>
                    <div class="flex justify-between text-[9px] text-gray-500 mt-1 font-mono">
                        <span>0</span>
                        <span id="mid-legend">-</span>
                        <span id="max-legend">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="map"></div>

    <script>
        const cRojo = [239, 68, 68]; const cAmarillo = [234, 179, 8]; const cVerde = [34, 197, 94];

        function interpolateColor(color1, color2, factor) {
            var result = color1.slice();
            for (var i = 0; i < 3; i++) {
                result[i] = Math.round(result[i] + factor * (color2[i] - color1[i]));
            }
            return 'rgb(' + result[0] + ',' + result[1] + ',' + result[2] + ')';
        }

        function getGradientColor(alt, minAlt, maxAlt) {
            if (maxAlt === minAlt) return 'rgb(' + cAmarillo.join(',') + ')';
            var pct = (alt - minAlt) / (maxAlt - minAlt);
            pct = Math.max(0, Math.min(1, pct));
            if (pct < 0.5) return interpolateColor(cRojo, cAmarillo, pct * 2);
            else return interpolateColor(cAmarillo, cVerde, (pct - 0.5) * 2);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const rawData = {!! json_encode($flightData) !!};

            if (!rawData || rawData.length === 0) {
                alert("Archivo de vuelo vacío.");
                return;
            }

            const flightData = rawData.map(p => ({
                lat: parseFloat(p.lat || p.latitude || 0),
                lon: parseFloat(p.lon || p.longitude || 0),
                alt: Math.round(parseFloat(p.alt || p.altitude || p.elevation || 0)),
                hdg: Math.round(parseFloat(p.heading || p.hdg || p.mag_psi || 0)),
                spd: Math.round(parseFloat(p.speed || p.gs || p.ias || 0))
            })).filter(p => p.lat !== 0 && p.lon !== 0);

            let maxAlt = 0;
            let minAlt = 99999;
            flightData.forEach(p => {
                if(p.alt > maxAlt) maxAlt = p.alt;
                if(p.alt < minAlt) minAlt = p.alt;
            });
            
            document.getElementById('max-alt').innerText = maxAlt;
            const durationMin = (flightData.length / 60).toFixed(1);
            document.getElementById('duration').innerText = durationMin;
            
            document.getElementById('mid-legend').innerText = Math.round((maxAlt + minAlt) / 2);
            document.getElementById('max-legend').innerText = maxAlt + "+";

            const startPoint = flightData[0];
            const map = L.map('map', {zoomControl: false}).setView([startPoint.lat, startPoint.lon], 14);
            L.control.zoom({position: 'topright'}).addTo(map);

            L.tileLayer('/mapas/mapas_naval/{z}/{x}/{y}.png', {
                minZoom: 10, maxZoom: 16, tms: true, attribution: 'Escuela Naval',
                errorTileUrl: '', updateWhenIdle: false
            }).addTo(map);

            const totalPoints = flightData.length;
            const step = Math.ceil(totalPoints / 2000) || 1;
            const allCoords = [];

            for (let i = 0; i < totalPoints - step; i += step) {
                const p1 = flightData[i];
                const p2 = flightData[i + step];
                const color = getGradientColor(p1.alt, minAlt, maxAlt);
                L.polyline([[p1.lat, p1.lon], [p2.lat, p2.lon]], {
                    color: color, weight: 5, opacity: 0.8, smoothFactor: 1
                }).addTo(map);
                allCoords.push([p1.lat, p1.lon]);
            }
            
            if(allCoords.length > 0) {
                map.fitBounds(L.polyline(allCoords).getBounds(), {padding: [50, 50]});
            }

            // --- CONFIGURACIÓN DEL AVIÓN GRANDE ---
            const baseIconSize = 64; // Tamaño Base Grande (antes 40)
            
            const planeIcon = L.divIcon({
                className: 'plane-icon-container',
                // Icono más grande y centrado
                html: `<img src="/images/VueloPC7.png" id="planeImg" style="width: ${baseIconSize}px; height: ${baseIconSize}px; display:block;" onerror="this.src='https://cdn-icons-png.flaticon.com/512/7893/7893979.png'">`,
                iconSize: [baseIconSize, baseIconSize],
                iconAnchor: [baseIconSize/2, baseIconSize/2]
            });
            const planeMarker = L.marker([startPoint.lat, startPoint.lon], {icon: planeIcon, zIndexOffset: 2000}).addTo(map);

            // --- LÓGICA DE ZOOM DINÁMICO ---
            // Escuchamos cuando el usuario hace zoom
            map.on('zoomend', function() {
                var currentZoom = map.getZoom();
                var img = document.getElementById('planeImg');
                if(img) {
                    // Calculamos una escala: A más zoom, el avión se ve un poco más grande
                    // Zoom 14 es el "normal" (escala 1)
                    var scale = 1 + (currentZoom - 14) * 0.2; 
                    // Limitamos para que no sea ni microscópico ni gigante
                    scale = Math.max(0.5, Math.min(2.5, scale));
                    
                    // Aplicamos el tamaño sin perder la rotación (se aplica en updateFrame)
                    img.dataset.scale = scale; // Guardamos la escala para usarla al rotar
                }
            });

            // --- REPRODUCTOR ---
            let currentIndex = 0;
            let isPlaying = false;
            let playInterval;
            let speedMs = 100;
            
            const slider = document.getElementById('timeSlider');
            const elAlt = document.getElementById('liveAlt');
            const elSpd = document.getElementById('liveSpd');
            const elHdg = document.getElementById('liveHdg');
            const elTime = document.getElementById('currentTime');
            const btnPlay = document.getElementById('btnPlay');
            const btnPause = document.getElementById('btnPause');

            slider.max = totalPoints - 1;
            const formatTime = (sec) => new Date(sec * 1000).toISOString().substr(14, 5);
            document.getElementById('totalTime').innerText = formatTime(totalPoints);

            function updateFrame(index) {
                if (index >= totalPoints) return;
                const data = flightData[index];

                planeMarker.setLatLng([data.lat, data.lon]);
                
                const img = document.getElementById('planeImg');
                if(img) {
                    // Recuperamos la escala del zoom (o 1 por defecto)
                    const currentScale = img.dataset.scale || 1;
                    // Aplicamos Rotación + Escala de Zoom
                    img.style.transform = `rotate(${data.hdg}deg) scale(${currentScale})`;
                }

                elAlt.innerText = data.alt;
                elSpd.innerText = data.spd;
                elHdg.innerText = data.hdg;
                elTime.innerText = formatTime(index);
                slider.value = index;
            }

            function play() {
                if (currentIndex >= totalPoints - 1) currentIndex = 0;
                isPlaying = true;
                btnPlay.classList.add('hidden');
                btnPause.classList.remove('hidden');
                playInterval = setInterval(() => {
                    if (currentIndex < totalPoints - 1) {
                        currentIndex++;
                        updateFrame(currentIndex);
                    } else {
                        pause();
                    }
                }, speedMs);
            }

            function pause() {
                isPlaying = false;
                clearInterval(playInterval);
                btnPlay.classList.remove('hidden');
                btnPause.classList.add('hidden');
            }

            btnPlay.addEventListener('click', play);
            btnPause.addEventListener('click', pause);
            slider.addEventListener('input', (e) => {
                pause();
                currentIndex = parseInt(e.target.value);
                updateFrame(currentIndex);
            });
            document.getElementById('speedBtn').addEventListener('change', (e) => {
                speedMs = parseInt(e.target.value);
                if(isPlaying) { pause(); play(); }
            });

            updateFrame(0);
        });
    </script>
</body>
</html>