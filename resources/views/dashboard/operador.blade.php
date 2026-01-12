<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('🎛️ Panel de Operador') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('sesiones.scanner') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-2 transition-colors">
                    <span>⌨️</span> Nueva Sesión
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border-l-4 {{ $sesionesActivas->count() > 0 ? 'border-yellow-500' : 'border-green-500' }} transition-colors">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado Simulador</p>
                            <p class="text-xl font-bold {{ $sesionesActivas->count() > 0 ? 'text-yellow-600 dark:text-yellow-500' : 'text-green-600 dark:text-green-400' }}">
                                {{ $sesionesActivas->count() > 0 ? '🔵 EN USO' : '⚪ LIBRE' }}
                            </p>
                        </div>
                        <div class="text-2xl">✈️</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border-l-4 border-blue-500 transition-colors">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Vuelos Hoy</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $estadisticas['sesiones_hoy'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-full text-blue-500 text-xl">📅</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border-l-4 border-indigo-500 transition-colors">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Horas Voladas</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ isset($estadisticas['tiempo_total_hoy']) ? round($estadisticas['tiempo_total_hoy'] / 60, 1) : 0 }}h
                            </p>
                        </div>
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-full text-indigo-500 text-xl">⏱️</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border-l-4 border-purple-500 transition-colors">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pilotos Hoy</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $estadisticas['alumnos_hoy'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-full text-purple-500 text-xl">🎓</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 lg:col-span-1 border-t-4 {{ $sesionesActivas->count() > 0 ? 'border-yellow-500' : 'border-blue-500' }} transition-colors">
                    
                    @if($sesionesActivas->count() > 0)
                        {{-- CASO 1: VUELO EN CURSO --}}
                        @php $sesionActual = $sesionesActivas->first(); @endphp
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-500 flex items-center gap-2">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-600 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-600"></span>
                                </span>
                                Vuelo en Curso
                            </h3>
                            <span class="text-xs font-bold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 px-2 py-1 rounded border border-yellow-200 dark:border-yellow-800">EN VIVO</span>
                        </div>

                        <div class="text-center">
                            <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ $sesionActual->alumno->nombre_completo }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">NPI: {{ $sesionActual->alumno->npi }}</p>
                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded p-4 mb-4 border border-yellow-200 dark:border-yellow-600/30 shadow-inner">
                                <p class="text-xs text-gray-500 dark:text-gray-300 uppercase font-bold">Tiempo Transcurrido</p>
                                <p class="text-4xl font-mono font-bold text-yellow-600 dark:text-yellow-400 my-2 tracking-widest">{{ $sesionActual->tiempo_transcurrido }}</p>
                            </div>

                            <button type="button" 
                                    onclick="finalizarSesionAjax('{{ route('sesiones.finalizar-directa', $sesionActual->id) }}', '{{ $sesionActual->alumno->nombre_completo }}')"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded shadow transition flex items-center justify-center gap-2 cursor-pointer transform hover:scale-[1.02]">
                                <span>⏹</span> Finalizar Sesión
                            </button>
                        </div>

                    @elseif($sesionesRecientes->count() > 0)
                        {{-- CASO 2: ÚLTIMO VUELO --}}
                        @php 
                            $ultimoVuelo = $sesionesRecientes->first();
                            $archivoExisteCard = false;
                            if($ultimoVuelo->archivo_vuelo) {
                                $ruta = public_path('vuelos/' . $ultimoVuelo->archivo_vuelo);
                                if(file_exists($ruta) && filesize($ruta) > 1000) {
                                    $archivoExisteCard = true;
                                }
                            }
                        @endphp
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">🛬 Último Vuelo</h3>
                            <span class="text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded border border-gray-200 dark:border-gray-600">Finalizado</span>
                        </div>

                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl shadow-sm">
                                🏁
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $ultimoVuelo->alumno->nombre_completo }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">NPI: {{ $ultimoVuelo->alumno->npi }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4 font-mono">{{ $ultimoVuelo->fecha->format('d M Y') }} • {{ $ultimoVuelo->hora_inicio->format('H:i') }}</p>
                            
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded p-3 mb-4 text-left border border-gray-100 dark:border-gray-600">
                                <p class="text-xs text-gray-500 dark:text-gray-300 uppercase font-bold">Actividad</p>
                                <p class="text-sm text-gray-800 dark:text-gray-200 truncate font-medium">{{ $ultimoVuelo->actividad }}</p>
                                <div class="mt-2 flex justify-between text-sm border-t border-gray-200 dark:border-gray-600 pt-2">
                                    <span class="text-gray-600 dark:text-gray-400 font-mono">⏱️ {{ $ultimoVuelo->duracion_formateada }}</span>
                                    <span class="font-bold text-green-600 dark:text-green-400">Finalizada</span>
                                </div>
                            </div>

                            @if($archivoExisteCard)
                                <a href="{{ route('vuelos.show', $ultimoVuelo->archivo_vuelo) }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                    🗺️ Ver Mapa de Vuelo
                                </a>
                            @else
                                <button disabled class="block w-full bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed border border-gray-200 dark:border-gray-600">
                                    🚫 Sin Telemetría
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-400 dark:text-gray-500">
                            <div class="text-4xl mb-3 opacity-50">✈️</div>
                            <p>No hay vuelos registrados aún.</p>
                        </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 lg:col-span-2 flex flex-col h-full transition-colors">
                    
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
                            $maxSesiones = $collection->max('sesiones') ?: 1;
                            $maxMinutos = $collection->max('minutos') ?: 1;
                        @endphp

                        @foreach($estadisticasSemana as $dia)
                            @php 
                                $cantSesiones = $dia['sesiones'];
                                $pctSesiones = ($cantSesiones / $maxSesiones) * 100;
                                $alturaSesion = $cantSesiones == 0 ? 2 : $pctSesiones;

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
                                        {{-- AQUI ESTÁ LA CORRECCIÓN CLAVE: Usamos datos limpios del Controller --}}
                                        {{ $dia['dia_nombre'] }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 dark:text-gray-500 font-bold mt-0.5">
                                        {{ $dia['fecha_corta'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors">
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
                            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-2 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded px-2 -mx-2 transition-colors">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $sesion->alumno->nombre_completo }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $sesion->fecha->format('d/m') }} - {{ Str::limit($sesion->actividad, 25) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="block text-xs font-bold text-gray-700 dark:text-gray-300 font-mono">{{ $sesion->duracion_formateada }}</span>
                                    @if($tieneArchivo)
                                        <a href="{{ route('vuelos.show', $sesion->archivo_vuelo) }}" class="text-[10px] text-blue-500 dark:text-blue-400 hover:text-blue-700 font-bold">▶ Ver Mapa</a>
                                    @else
                                        <span class="text-[10px] text-gray-400 dark:text-gray-600 italic" title="Archivo vacío o compartido">Sin Mapa</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Sin historial.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 transition-colors">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">🏆 Ranking Pilotos (Mes)</h3>
                    <div class="space-y-3">
                        @foreach($alumnosActivos->take(5) as $index => $alumno)
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded transition border border-transparent hover:border-gray-100 dark:hover:border-gray-600">
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
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                        {{ $alumno->sesiones_count }}
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
        function finalizarSesionAjax(url, nombreAlumno) {
            if (!confirm(`¿CONFIRMAR: Finalizar la sesión de ${nombreAlumno}?`)) {
                return;
            }
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Hubo un error al intentar finalizar la sesión.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión.');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Recargar cada 60 segundos para mantener el estado actualizado
            setInterval(function(){ window.location.reload(); }, 60000);
        });
    </script>
</x-app-layout>