<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('🎛️ Panel de Operador') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('sesiones.scanner') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-2">
                    <span>⌨️</span> Nueva Sesión
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 {{ $sesionesActivas->count() > 0 ? 'border-yellow-500' : 'border-green-500' }}">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Estado Simulador</p>
                            <p class="text-xl font-bold {{ $sesionesActivas->count() > 0 ? 'text-yellow-600' : 'text-green-600' }}">
                                {{ $sesionesActivas->count() > 0 ? '🔵 EN USO' : '⚪ LIBRE' }}
                            </p>
                        </div>
                        <div class="text-2xl">✈️</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-blue-500">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Vuelos Hoy</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['sesiones_hoy'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full text-blue-500 text-xl">📅</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-indigo-500">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Horas Voladas</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ isset($estadisticas['tiempo_total_hoy']) ? round($estadisticas['tiempo_total_hoy'] / 60, 1) : 0 }}h
                            </p>
                        </div>
                        <div class="p-3 bg-indigo-50 rounded-full text-indigo-500 text-xl">⏱️</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-purple-500">
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pilotos Hoy</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $estadisticas['alumnos_hoy'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-full text-purple-500 text-xl">🎓</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="bg-white shadow-md rounded-lg p-6 lg:col-span-1 border-t-4 {{ $sesionesActivas->count() > 0 ? 'border-yellow-500' : 'border-blue-500' }}">
                    
                    @if($sesionesActivas->count() > 0)
                        {{-- CASO 1: VUELO EN CURSO --}}
                        @php $sesionActual = $sesionesActivas->first(); @endphp
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-yellow-800 flex items-center gap-2">
                                <span class="relative flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-600 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-600"></span>
                                </span>
                                Vuelo en Curso
                            </h3>
                            <span class="text-xs font-bold bg-yellow-200 text-yellow-800 px-2 py-1 rounded">EN VIVO</span>
                        </div>

                        <div class="text-center">
                            <h4 class="text-xl font-bold text-gray-900">{{ $sesionActual->alumno->nombre_completo }}</h4>
                            <p class="text-sm text-gray-600 mb-2">NPI: {{ $sesionActual->alumno->npi }}</p>
                            
                            <div class="bg-white rounded p-4 mb-4 border border-yellow-200 shadow-sm">
                                <p class="text-xs text-gray-500 uppercase font-bold">Tiempo Transcurrido</p>
                                <p class="text-4xl font-mono font-bold text-yellow-600 my-2">{{ $sesionActual->tiempo_transcurrido }}</p>
                            </div>

                            {{-- IMPORTANTE: Aquí está la clave para que funcione igual que el Scanner --}}
                            
                            <form id="form-finalizar-{{ $sesionActual->id }}" 
                                  action="{{ route('sesiones.finalizar', $sesionActual->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                            </form>

                            {{-- SOLUCIÓN FINAL: Usamos la ruta 'finalizar-directa' que sí tiene permiso el Operador --}}
                            <form action="{{ route('sesiones.finalizar-directa', $sesionActual->id) }}" method="POST">
                                @csrf
                                <button type="button" 
                                    onclick="finalizarSesionAjax('{{ route('sesiones.finalizar-directa', $sesionActual->id) }}', '{{ $sesionActual->alumno->nombre_completo }}')"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded shadow transition flex items-center justify-center gap-2 cursor-pointer">
                                    ⏹ Finalizar Sesión
                                </button>
                            </form>
                        </div>

                    @elseif($sesionesRecientes->count() > 0)
                        {{-- CASO 2: MOSTRAR ÚLTIMO VUELO --}}
                        @php 
                            $ultimoVuelo = $sesionesRecientes->first();
                            // Validación del archivo para la tarjeta principal
                            $archivoExisteCard = false;
                            if($ultimoVuelo->archivo_vuelo) {
                                $ruta = public_path('vuelos/' . $ultimoVuelo->archivo_vuelo);
                                if(file_exists($ruta) && filesize($ruta) > 1000) {
                                    $archivoExisteCard = true;
                                }
                            }
                        @endphp
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800">🛬 Último Vuelo</h3>
                            <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-1 rounded">Finalizado</span>
                        </div>

                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3 text-3xl text-blue-500">
                                🏁
                            </div>
                            <h4 class="text-lg font-bold text-gray-900">{{ $ultimoVuelo->alumno->nombre_completo }}</h4>
                            <p class="text-sm text-gray-500 mb-1">NPI: {{ $ultimoVuelo->alumno->npi }}</p>
                            <p class="text-xs text-gray-400 mb-4">{{ $ultimoVuelo->fecha->format('d M Y') }} • {{ $ultimoVuelo->hora_inicio->format('H:i') }}</p>
                            
                            <div class="bg-gray-50 rounded p-3 mb-4 text-left">
                                <p class="text-xs text-gray-500 uppercase font-bold">Actividad</p>
                                <p class="text-sm text-gray-800 truncate">{{ $ultimoVuelo->actividad }}</p>
                                <div class="mt-2 flex justify-between text-sm">
                                    <span>⏱️ {{ $ultimoVuelo->duracion_formateada }}</span>
                                    <span class="font-bold text-green-600">Finalizada</span>
                                </div>
                            </div>

                            @if($archivoExisteCard)
                                <a href="{{ route('vuelos.show', $ultimoVuelo->archivo_vuelo) }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition">
                                    🗺️ Ver Mapa de Vuelo
                                </a>
                            @else
                                <button disabled class="block w-full bg-gray-200 text-gray-400 font-bold py-2 px-4 rounded cursor-not-allowed">
                                    🚫 Sin Telemetría (Vuelo Compartido)
                                </button>
                            @endif
                        </div>
                    @else
                        {{-- CASO 3: SIN DATOS --}}
                        <div class="text-center py-10 text-gray-400">
                            <p>No hay vuelos registrados aún.</p>
                        </div>
                    @endif
                </div>

                <div class="bg-white shadow-md rounded-lg p-6 lg:col-span-2 flex flex-col h-full">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">📊 Actividad Semanal</h3>
                    
                    <div class="flex items-end justify-between gap-3 mt-auto w-full" style="height: 280px;">
                        @foreach($estadisticasSemana as $dia)
                            @php 
                                $cantidad = $dia['sesiones'];
                                $maxSesiones = collect($estadisticasSemana)->max('sesiones') ?: 1;
                                $porcentaje = ($cantidad / $maxSesiones) * 100;
                                // Altura visual mínima del 5% para que la barra gris se vea
                                $alturaVisual = $cantidad == 0 ? 5 : $porcentaje; 
                            @endphp
                            
                            <div class="flex flex-col items-center w-full h-full justify-end group cursor-pointer relative">
                                
                                <div class="mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-gray-900 text-white text-xs rounded py-1 px-2 absolute top-0 z-10 whitespace-nowrap shadow-lg pointer-events-none transform -translate-y-full">
                                    {{ round($dia['minutos']/60, 1) }} horas
                                </div>

                                <div class="mb-1 text-sm font-bold text-blue-600 {{ $cantidad > 0 ? 'opacity-100' : 'opacity-0' }} transition-opacity">
                                    {{ $cantidad }}
                                </div>
                                
                                <div class="w-full relative flex items-end justify-center bg-gray-50 rounded-md hover:bg-gray-100 transition-colors" style="height: 200px;">
                                    <div style="height: {{ $alturaVisual }}%;" 
                                         class="w-full max-w-[30px] rounded-t-md transition-all duration-700 ease-out relative 
                                         {{ $cantidad > 0 ? 'bg-blue-600 shadow-md group-hover:bg-blue-500' : 'bg-gray-200' }}">
                                    </div>
                                </div>
                                
                                <div class="mt-3 text-center">
                                    <div class="text-[11px] font-bold uppercase text-gray-600">
                                        {{ ucfirst(\Carbon\Carbon::createFromFormat('d/m', $dia['fecha'])->locale('es')->isoFormat('ddd')) }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $dia['fecha'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">📋 Historial Reciente</h3>
                        <a href="{{ route('sesiones.index') }}" class="text-sm text-blue-600 hover:underline">Ver todo</a>
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
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2 last:border-0">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $sesion->alumno->nombre_completo }}</p>
                                    <p class="text-xs text-gray-500">{{ $sesion->fecha->format('d/m') }} - {{ Str::limit($sesion->actividad, 25) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="block text-xs font-bold text-gray-700">{{ $sesion->duracion_formateada }}</span>
                                    @if($tieneArchivo)
                                        <a href="{{ route('vuelos.show', $sesion->archivo_vuelo) }}" class="text-[10px] text-blue-500 hover:text-blue-700 font-bold">▶ Ver Mapa</a>
                                    @else
                                        <span class="text-[10px] text-gray-400 italic" title="Archivo vacío o compartido">Sin Mapa</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Sin historial.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">🏆 Ranking Pilotos (Mes)</h3>
                    <div class="space-y-3">
                        @foreach($alumnosActivos->take(5) as $index => $alumno)
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded transition">
                                <div class="flex items-center">
                                    <div class="w-8 text-center font-bold text-gray-400">
                                        @if($index == 0) 🥇 @elseif($index == 1) 🥈 @elseif($index == 2) 🥉 @else #{{ $index + 1 }} @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-bold text-gray-800">{{ $alumno->nombre_completo }}</p>
                                        <p class="text-xs text-gray-500">{{ $alumno->sesiones_count }} sesiones</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
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
            // 1. Confirmar Acción
            if (!confirm(`¿CONFIRMAR: Finalizar la sesión de ${nombreAlumno}?`)) {
                return; // Si cancela, no hacemos nada
            }

            // 2. Enviar petición al servidor "en segundo plano"
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Llave de seguridad de Laravel
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // 3. ¡Éxito! Recargamos la página para ver los cambios
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

        // Recarga automática cada 60s (Mantener)
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(function(){ window.location.reload(); }, 60000);
        });
    </script>
</x-app-layout>