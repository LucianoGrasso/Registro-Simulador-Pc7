<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('📊 Panel de Control General') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('reportes.index') }}" class="bg-blue-700 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                    📈 Reportes
                </a>
                <a href="{{ route('sesiones.scanner') }}" class="bg-red-700 hover:bg-red-600 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                    🎓 Nueva Sesión
                </a>
                <a href="http://localhost:8081" class="bg-green-700 hover:bg-green-600 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                    Iniciar IDU Genesys
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border-l-4 border-blue-500 transition-colors duration-300">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sesiones Hoy</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white" id="sesiones-hoy">{{ $estadisticas['total_sesiones_hoy'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-full text-blue-500 text-xl">📅</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border-l-4 border-green-500 transition-colors duration-300">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Horas Voladas Hoy</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white" id="tiempo-total">{{ round($estadisticas['tiempo_total_hoy'] / 60, 1) }}h</p>
                        </div>
                        <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-full text-green-500 text-xl">⏱️</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border-l-4 border-purple-500 transition-colors duration-300">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Alumnos Totales</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $estadisticas['total_alumnos'] }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-full text-purple-500 text-xl">🎓</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border-l-4 {{ $estadisticas['sesiones_activas'] > 0 ? 'border-yellow-500' : 'border-gray-300 dark:border-gray-600' }} transition-colors duration-300">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Simulador Ahora</p>
                            <p class="text-xl font-bold {{ $estadisticas['sesiones_activas'] > 0 ? 'text-yellow-600 dark:text-yellow-500' : 'text-gray-400 dark:text-gray-500' }}" id="sesiones-activas">
                                {{ $estadisticas['sesiones_activas'] > 0 ? '🔵 EN USO' : '⚪ LIBRE' }}
                            </p>
                        </div>
                        <div class="p-3 {{ $estadisticas['sesiones_activas'] > 0 ? 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-500' : 'bg-gray-100 dark:bg-gray-700 text-gray-400' }} rounded-full text-xl">
                            ✈️
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 lg:col-span-1 border-t-4 border-indigo-500 transition-colors duration-300">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">🛬 Último Vuelo</h3>
                        <span class="text-xs font-semibold bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-2 py-1 rounded">Reciente</span>
                    </div>

                    @if($sesionesRecientes->count() > 0)
                        @php 
                            $ultimoVuelo = $sesionesRecientes->first(); 
                            $archivoValidoCard = false;
                            if($ultimoVuelo->archivo_vuelo) {
                                $ruta = public_path('vuelos/' . $ultimoVuelo->archivo_vuelo);
                                if(file_exists($ruta) && filesize($ruta) > 1000) {
                                    $archivoValidoCard = true;
                                }
                            }
                        @endphp

                        <div class="text-center">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $ultimoVuelo->alumno->nombre_completo }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">NPI: {{ $ultimoVuelo->alumno->npi }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">{{ $ultimoVuelo->fecha->format('d M Y') }} • {{ $ultimoVuelo->hora_inicio->format('H:i') }}</p>
                            
                            <div class="bg-gray-50 dark:bg-gray-700 rounded p-3 mb-4 text-left border dark:border-gray-600">
                                <p class="text-xs text-gray-500 dark:text-gray-300 uppercase font-bold">Actividad</p>
                                <p class="text-sm text-gray-800 dark:text-gray-200 truncate">{{ $ultimoVuelo->actividad }}</p>
                                <div class="mt-2 flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">⏱️ {{ $ultimoVuelo->duracion_formateada }}</span>
                                    <span class="font-bold {{ $ultimoVuelo->estado == 'finalizada' ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                        {{ ucfirst($ultimoVuelo->estado) }}
                                    </span>
                                </div>
                            </div>

                            @if($archivoValidoCard)
                                <a href="{{ route('vuelos.show', $ultimoVuelo->archivo_vuelo) }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition">
                                    🗺️ Ver Mapa de Vuelo
                                </a>
                            @else
                                <button disabled class="block w-full bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed border border-gray-300 dark:border-gray-600">
                                    🚫 Sin Telemetría
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-400 dark:text-gray-500">
                            <p>No hay vuelos registrados aún.</p>
                        </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 lg:col-span-2 flex flex-col h-full transition-colors duration-300">
    
                    <div class="mb-5 flex flex-col sm:flex-row justify-between items-start sm:items-end">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">📊 Rendimiento Semanal</h3>
                        
                        <div class="flex items-center gap-4 mt-2 sm:mt-0 bg-gray-50 dark:bg-gray-700 px-3 py-1 rounded-lg border border-gray-100 dark:border-gray-600">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-blue-600 rounded-sm"></span>
                                <span class="text-xs text-gray-600 dark:text-gray-300 font-bold uppercase">Sesiones</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 bg-emerald-500 rounded-sm"></span>
                                <span class="text-xs text-gray-600 dark:text-gray-300 font-bold uppercase">Horas</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-end justify-between gap-2 mt-auto w-full" style="height: 280px;">
                        
                        @php
                            $collection = collect($estadisticasSemana);
                            // Evitamos división por cero asegurando un mínimo de 1
                            $maxSesiones = $collection->max('sesiones') ?: 1;
                            $maxMinutos = $collection->max('minutos') ?: 1;
                        @endphp

                        @foreach($estadisticasSemana as $dia)
                            @php 
                                // Cálculos para Sesiones
                                $cantSesiones = $dia['sesiones'];
                                $pctSesiones = ($cantSesiones / $maxSesiones) * 100;
                                $alturaSesion = $cantSesiones == 0 ? 2 : $pctSesiones;

                                // Cálculos para Minutos/Horas
                                $minutos = $dia['minutos'];
                                $horas = round($minutos / 60, 1);
                                $pctHoras = ($minutos / $maxMinutos) * 100;
                                $alturaHoras = $minutos == 0 ? 2 : $pctHoras;
                            @endphp
                            
                            <div class="flex flex-col items-center w-full h-full justify-end group cursor-pointer relative">
                                
                                <div class="mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded py-2 px-3 absolute top-0 z-20 whitespace-nowrap shadow-xl pointer-events-none transform -translate-y-full text-center border border-gray-700 dark:border-gray-500">
                                    <div class="font-bold text-blue-200 text-sm">{{ $cantSesiones }} Vuelos</div>
                                    <div class="font-bold text-emerald-200 text-sm">{{ $horas }} Horas</div>
                                </div>

                                <div class="flex items-end justify-center gap-1 w-full max-w-[60px] bg-gray-50 dark:bg-gray-700/50 rounded-lg pb-0 border-b border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" style="height: 220px;">
                                    
                                    <div class="relative w-1/2 flex flex-col items-center justify-end h-full">
                                        <div class="mb-1 text-base sm:text-lg font-black text-blue-800 dark:text-blue-400 {{ $cantSesiones > 0 ? 'opacity-100' : 'opacity-0' }}">
                                            {{ $cantSesiones }}
                                        </div>
                                        <div style="height: {{ $alturaSesion }}%;" 
                                            class="w-full rounded-t-sm transition-all duration-700 ease-out 
                                            {{ $cantSesiones > 0 ? 'bg-blue-600 shadow-md' : 'bg-blue-100/50 dark:bg-blue-900/30' }}">
                                        </div>
                                    </div>

                                    <div class="relative w-1/2 flex flex-col items-center justify-end h-full">
                                        <div class="mb-1 text-xs sm:text-sm font-extrabold text-emerald-700 dark:text-emerald-400 {{ $horas > 0 ? 'opacity-100' : 'opacity-0' }}">
                                            {{ $horas }}h
                                        </div>
                                        <div style="height: {{ $alturaHoras }}%;" 
                                            class="w-full rounded-t-sm transition-all duration-700 ease-out 
                                            {{ $minutos > 0 ? 'bg-emerald-500 shadow-sm' : 'bg-emerald-100/50 dark:bg-emerald-900/30' }}">
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="mt-3 text-center">
                                    <div class="text-[12px] font-black uppercase text-gray-700 dark:text-gray-400">
                                        {{-- Usamos la variable directa que viene del controlador --}}
                                        {{ $dia['dia_nombre'] }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 dark:text-gray-500 font-bold mt-0.5">
                                        {{-- Usamos la variable formateada --}}
                                        {{ $dia['fecha_corta'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors duration-300">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">📋 Historial Reciente</h3>
                        <a href="{{ route('sesiones.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Ver todo</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($sesionesRecientes->take(5) as $sesion)
                            @php
                                $tieneArchivo = false;
                                if($sesion->archivo_vuelo) {
                                    $ruta = public_path('vuelos/' . $sesion->archivo_vuelo);
                                    if(file_exists($ruta) && filesize($ruta) > 1000) {
                                        $tieneArchivo = true;
                                    }
                                }
                            @endphp

                            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-2 last:border-0">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $sesion->alumno->nombre_completo }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $sesion->fecha->format('d/m') }} - {{ Str::limit($sesion->actividad, 30) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="block text-xs font-bold text-gray-700 dark:text-gray-300">{{ $sesion->duracion_formateada }}</span>
                                    
                                    @if($tieneArchivo)
                                        <a href="{{ route('vuelos.show', $sesion->archivo_vuelo) }}" class="text-[10px] text-blue-500 dark:text-blue-400 hover:text-blue-700 font-bold">
                                            ▶ Ver Mapa
                                        </a>
                                    @else
                                        <span class="text-[10px] text-gray-400 dark:text-gray-600 italic cursor-help" title="Archivo vacío o no generado">
                                            Sin Mapa
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sin historial.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors duration-300">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">🏆 Top Pilotos (Mes)</h3>
                    
                    <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($alumnosActivos as $index => $alumno)
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded transition">
                                <div class="flex items-center">
                                    <div class="w-8 text-center font-bold text-gray-400 dark:text-gray-500">
                                        @if($index == 0) 🥇 @elseif($index == 1) 🥈 @elseif($index == 2) 🥉 @else #{{ $index + 1 }} @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $alumno->nombre_completo }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $alumno->sesiones_count }} sesiones</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        Activo
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function actualizarDashboard() {
                fetch('{{ route("dashboard.datos") }}')
                    .then(response => response.json())
                    .then(data => {
                        // Actualizar contadores simples
                        document.getElementById('sesiones-hoy').textContent = data.estadisticas.sesiones_hoy;
                        document.getElementById('tiempo-total').textContent = (Math.round(data.estadisticas.tiempo_total_hoy / 60 * 10) / 10) + 'h';
                        
                        // Actualizar estado del simulador (Con soporte Dark Mode)
                        const estadoElem = document.getElementById('sesiones-activas');
                        if(data.estadisticas.sesiones_activas > 0) {
                            estadoElem.textContent = '🔵 EN USO';
                            // Importante: agregamos la clase dark para que no se rompa el estilo al actualizar
                            estadoElem.className = 'text-xl font-bold text-yellow-600 dark:text-yellow-500';
                        } else {
                            estadoElem.textContent = '⚪ LIBRE';
                            estadoElem.className = 'text-xl font-bold text-gray-400 dark:text-gray-500';
                        }
                    })
                    .catch(console.error);
            }
            // Actualizar cada 60 segundos
            setInterval(actualizarDashboard, 60000);
        });
    </script>
</x-app-layout>