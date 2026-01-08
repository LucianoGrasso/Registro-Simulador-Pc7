<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{-- Título Seguro --}}
                {{ __('🗺️ Análisis de Vuelo: ') . ($sesion->alumno->nombre_completo ?? 'Archivo Externo') }}
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $archivoNombre }}</span>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-blue-500 transition-colors">
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase mb-1">Pitch (Nariz)</div>
                    <div class="flex justify-between items-end">
                        <div class="text-gray-800 dark:text-white"><span class="text-xs text-gray-400">Máx</span> ⬆ {{ number_format($stats['pitch_max'], 1) }}°</div>
                        <div class="text-gray-800 dark:text-white"><span class="text-xs text-gray-400">Mín</span> ⬇ {{ number_format($stats['pitch_min'], 1) }}°</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-indigo-500 transition-colors">
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase mb-1">Roll (Alabeo)</div>
                    <div class="flex justify-between items-end">
                        <div class="text-gray-800 dark:text-white"><span class="text-xs text-gray-400">Izq</span> ⬅ {{ number_format(abs($stats['roll_min']), 1) }}°</div>
                        <div class="text-gray-800 dark:text-white"><span class="text-xs text-gray-400">Der</span> ➡ {{ number_format($stats['roll_max'], 1) }}°</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500 transition-colors">
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase mb-1">Altitud Máxima</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['alt_max'], 0) }} <span class="text-xs">ft</span></div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-red-500 transition-colors">
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase mb-1">Velocidad Máx</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['gs_max'], 0) }} <span class="text-xs">kts</span></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                
                <div class="lg:col-span-3 bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border dark:border-gray-700 relative transition-colors" style="height: 500px;">
                    <div id="map" style="width: 100%; height: 100%; z-index: 1;"></div>
                    
                    <div class="absolute bottom-6 right-4 z-[400] bg-white/90 dark:bg-gray-800/90 p-3 rounded-lg shadow-md border border-gray-300 dark:border-gray-600 backdrop-blur-sm">
                        <div class="flex flex-col items-center gap-1">
                            <div class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Altitud (MSL)</div>
                            <div class="w-32 h-3 rounded-full" style="background: linear-gradient(to right, hsl(0, 70%, 45%), hsl(60, 70%, 45%), hsl(120, 70%, 45%));"></div>
                            <div class="flex justify-between w-full text-[11px] font-mono font-bold text-gray-600 dark:text-gray-400 mt-1">
                                <span id="legend-min">0 ft</span> <span id="legend-max">0 ft</span> </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 flex flex-col gap-4">
                    <div class="bg-gray-800 text-white rounded-lg shadow-lg p-5 border border-gray-700 h-full">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-600 pb-2">
                            Telemetría en Vivo
                        </h4>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="text-center">
                                <div class="text-xs text-gray-400">SPD (Kts)</div>
                                <div class="text-3xl font-mono font-bold text-green-400" id="live-spd">0</div>
                            </div>
                            <div class="text-center border-l border-gray-600">
                                <div class="text-xs text-gray-400">ALT (Ft)</div>
                                <div class="text-3xl font-mono font-bold text-green-400" id="live-alt">0</div>
                            </div>
                        </div>
                        <div class="mb-6 text-center bg-gray-900 rounded p-2">
                            <div class="text-xs text-gray-400 mb-1">RUMBO (HDG)</div>
                            <div class="text-2xl font-mono font-bold text-yellow-400 flex justify-center items-center gap-2">
                                <span id="live-hdg-icon" class="transform transition-transform duration-300">⬆</span>
                                <span id="live-hdg">000</span>°
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-gray-300">PITCH</span>
                                    <span class="font-mono font-bold text-blue-300" id="live-pitch">0.0°</span>
                                </div>
                                <div class="w-full bg-gray-600 h-1.5 rounded-full overflow-hidden relative">
                                    <div id="bar-pitch" class="absolute top-0 bottom-0 w-1 bg-blue-400 transition-all duration-100" style="left: 50%;"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-gray-300">ROLL</span>
                                    <span class="font-mono font-bold text-blue-300" id="live-roll">0.0°</span>
                                </div>
                                <div class="w-full bg-gray-600 h-1.5 rounded-full overflow-hidden relative">
                                    <div id="bar-roll" class="absolute top-0 bottom-0 w-1 bg-indigo-400 transition-all duration-100" style="left: 50%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border dark:border-gray-700 sticky bottom-4 z-50 transition-colors">
                <div class="flex items-center gap-4">
                    <button id="play-btn" class="flex-shrink-0 bg-blue-600 hover:bg-blue-700 text-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition transform hover:scale-105 focus:outline-none">
                        <span id="play-icon" class="text-sm">▶</span>
                    </button>
                    <div class="flex-shrink-0 w-12 text-center">
                        <span class="text-sm font-mono font-bold text-blue-600 dark:text-blue-400" id="current-time-display">00:00</span>
                    </div>
                    <div class="flex-1 w-full relative flex items-center">
                        <input type="range" id="timeline" min="0" max="100" value="0" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-blue-600 hover:accent-blue-500 transition-all">
                    </div>
                    <div class="flex-shrink-0 w-12 text-center">
                        <span class="text-sm font-mono font-bold text-gray-500 dark:text-gray-400" id="total-time-display">00:00</span>
                    </div>
                    <div class="flex-shrink-0 border-l border-gray-300 dark:border-gray-600 pl-4">
                        <select id="speed-select" class="bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 border-none rounded text-xs font-bold text-gray-700 dark:text-gray-200 py-1.5 px-2 cursor-pointer focus:ring-0">
                            <option value="1">1x</option>
                            <option value="2">2x</option>
                            <option value="5">5x</option>
                            <option value="10">10x</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- LIBRERÍAS OFFLINE --}}
    <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}" />
    <script src="{{ asset('leaflet/leaflet.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. OBTENER DATOS
            const rawData = @json($flightData);
            
            if (!rawData || rawData.length === 0) {
                console.error("Datos de vuelo vacíos");
                return;
            }

            // 2. INICIALIZAR MAPA
            const map = L.map('map').setView([rawData[0].lat, rawData[0].lon], 10);

            // ============================================================
            // 🗺️ CONFIGURACIÓN OFFLINE
            // ============================================================
            L.tileLayer('/mapas/mapas_naval/{z}/{x}/{y}.png', {
                minZoom: 8,
                maxZoom: 15,
                maxNativeZoom: 15,
                tms: true,
                attribution: '© PC-7 Offline Maps'
            }).addTo(map);

            // ----------------------------------------------------------------
            // 🎨 RASTRO DE COLORES SUAVE (Heatmap)
            // ----------------------------------------------------------------
            
            // a. Encontrar Altura Mínima y Máxima para calibrar
            let minAlt = Infinity;
            let maxAlt = -Infinity;
            rawData.forEach(p => {
                if(p.alt < minAlt) minAlt = p.alt;
                if(p.alt > maxAlt) maxAlt = p.alt;
            });

            // NUEVO: Mostrar los pies reales en la leyenda
            document.getElementById('legend-min').innerText = Math.round(minAlt) + ' ft';
            document.getElementById('legend-max').innerText = Math.round(maxAlt) + ' ft';

            // b. Función COLOR SUAVE
            function getColor(alt) {
                // Normalizar altura (0 a 1)
                let pct = (alt - minAlt) / (maxAlt - minAlt || 1); 
                
                // HSL: 0 (Rojo) -> 120 (Verde)
                // ANTES: Saturation 100%, Lightness 50% (Muy chillón)
                // AHORA: Saturation 70%, Lightness 45% (Más sobrio y profesional)
                let hue = pct * 120; 
                return `hsl(${hue}, 70%, 45%)`; 
            }

            // c. Dibujar segmentos
            for (let i = 0; i < rawData.length - 1; i++) {
                const p1 = rawData[i];
                const p2 = rawData[i+1];
                
                L.polyline([[p1.lat, p1.lon], [p2.lat, p2.lon]], {
                    color: getColor(p1.alt),
                    weight: 4,      // Línea un poco más delgada (era 5)
                    opacity: 0.8    // Un poco de transparencia
                }).addTo(map);
            }

            // Ajustar vista
            const fullLine = L.polyline(rawData.map(p => [p.lat, p.lon]), {opacity: 0});
            map.fitBounds(fullLine.getBounds(), {padding: [50, 50]});

            // ----------------------------------------------------------------
            // ✈️ MARCADOR DEL AVIÓN
            // ----------------------------------------------------------------
            const planeIcon = L.divIcon({
                html: '<img src="/images/VueloPC7.png" id="plane-img" style="width: 48px; height: 48px; transform-origin: center;">',
                className: 'plane-marker',
                iconSize: [48, 48],
                iconAnchor: [24, 24]
            });
            const marker = L.marker([rawData[0].lat, rawData[0].lon], {
                icon: planeIcon,
                zIndexOffset: 1000
            }).addTo(map);

            // 3. LOGICA REPRODUCTOR
            let isPlaying = false;
            let currentIndex = 0;
            const totalSeconds = rawData.length - 1;
            
            // Elementos DOM
            const elSpd = document.getElementById('live-spd');
            const elAlt = document.getElementById('live-alt');
            const elHdg = document.getElementById('live-hdg');
            const elHdgIcon = document.getElementById('live-hdg-icon');
            const elPitch = document.getElementById('live-pitch');
            const elRoll = document.getElementById('live-roll');
            const barPitch = document.getElementById('bar-pitch');
            const barRoll = document.getElementById('bar-roll');
            
            const elCurrentTime = document.getElementById('current-time-display');
            const elTotalTime = document.getElementById('total-time-display');
            const slider = document.getElementById('timeline');
            const btnPlay = document.getElementById('play-btn');
            const iconPlay = document.getElementById('play-icon');
            const selSpeed = document.getElementById('speed-select');

            slider.max = totalSeconds;
            
            function formatTime(seconds) {
                const min = Math.floor(seconds / 60).toString().padStart(2, '0');
                const sec = (seconds % 60).toString().padStart(2, '0');
                return `${min}:${sec}`;
            }
            elTotalTime.innerText = formatTime(totalSeconds);

            function updateDisplay(index) {
                if (!rawData[index]) return; 
                const data = rawData[index];

                marker.setLatLng([data.lat, data.lon]);
                
                const planeImg = document.getElementById('plane-img');
                if(planeImg) {
                    planeImg.style.transform = `rotate(${data.hdg}deg)`;
                }

                const speed = data.spd !== undefined ? data.spd : (data.gs || 0);
                elSpd.innerText = Math.round(speed);
                elAlt.innerText = Math.round(data.alt);
                elHdg.innerText = Math.round(data.hdg).toString().padStart(3, '0');
                elHdgIcon.style.transform = `rotate(${data.hdg}deg)`;

                const pitch = data.pitch || 0;
                const roll = data.roll || 0;
                elPitch.innerText = pitch.toFixed(1) + '°';
                elRoll.innerText = roll.toFixed(1) + '°';

                let pitchPct = 50 + (pitch * 1.5); 
                let rollPct = 50 + (roll * 1.5);
                barPitch.style.left = Math.max(0, Math.min(100, pitchPct)) + '%';
                barRoll.style.left = Math.max(0, Math.min(100, rollPct)) + '%';

                elCurrentTime.innerText = formatTime(index);
                slider.value = index;
            }

            function loop() {
                if (!isPlaying) return;
                if (currentIndex < totalSeconds) {
                    currentIndex++;
                    updateDisplay(currentIndex);
                    let delay = 1000 / parseInt(selSpeed.value); 
                    setTimeout(loop, delay);
                } else {
                    pause(); 
                }
            }

            function play() {
                if (currentIndex >= totalSeconds) currentIndex = 0; 
                isPlaying = true;
                iconPlay.innerText = '⏸';
                loop();
            }

            function pause() {
                isPlaying = false;
                iconPlay.innerText = '▶';
            }

            btnPlay.addEventListener('click', () => { if (isPlaying) pause(); else play(); });
            slider.addEventListener('input', (e) => { pause(); currentIndex = parseInt(e.target.value); updateDisplay(currentIndex); });

            updateDisplay(0);
            
            setTimeout(() => { map.invalidateSize(); }, 500);
        });
    </script>
</x-app-layout>