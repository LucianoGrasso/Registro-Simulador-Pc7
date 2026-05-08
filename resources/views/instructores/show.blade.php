<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('instructores.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Perfil de Instructor: <span class="text-indigo-600 dark:text-indigo-400">{{ $instructor->grado_nombre }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Encabezado del Perfil --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 sm:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-2xl border-2 border-indigo-200 dark:border-indigo-800">
                            👨‍✈️
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $instructor->grado_nombre }}</h3>
                            <div class="flex items-center gap-3 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-gray-700 dark:text-gray-300">NPI: {{ $instructor->npi }}</span>
                                @if($instructor->activo)
                                    <span class="text-green-600 dark:text-green-400 font-medium flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Activo</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400 font-medium flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Inactivo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjetas de Estadísticas --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors border border-gray-100 dark:border-gray-700">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">📋</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sesiones Impartidas</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $estadisticas['total_sesiones'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors border border-gray-100 dark:border-gray-700">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">⏱️</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Horas Acumuladas</p>
                                @php
                                    $minutos = $estadisticas['tiempo_total_minutos'];
                                    $horas_formateadas = floor($minutos / 60) . ':' . str_pad($minutos % 60, 2, '0', STR_PAD_LEFT);
                                @endphp
                                <p class="text-2xl font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ $horas_formateadas }} <span class="text-sm font-normal">hrs</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors border border-gray-100 dark:border-gray-700">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">📊</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Promedio por Sesión</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ round($estadisticas['promedio_duracion'], 0) }} <span class="text-sm font-normal text-gray-500">min</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors border border-gray-100 dark:border-gray-700">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">📅</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Última Instrucción</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">
                                    {{ $estadisticas['ultima_sesion'] ? $estadisticas['ultima_sesion']->fecha->format('d/m/Y') : 'Sin registros' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Historial de Vuelos (Tabla) --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">🛫 Historial de Instrucción</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alumno</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prueba / Actividad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duración</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($sesiones as $sesion)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        <div class="font-medium">{{ $sesion->fecha->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $sesion->hora_inicio->format('H:i') }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $sesion->alumno->nombre_completo ?? 'Alumno Eliminado' }}
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- Columna de Actividad + Código de Prueba --}}
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-2">
                                            @if($sesion->codigo_prueba)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold font-mono bg-indigo-100 text-indigo-800 border border-indigo-200 dark:bg-indigo-900/40 dark:text-indigo-300 dark:border-indigo-800 transition-colors">
                                                    {{ $sesion->codigo_prueba }}
                                                </span>
                                            @endif
                                            <span class="truncate max-w-[200px]" title="{{ $sesion->actividad }}">
                                                {{ $sesion->actividad }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        @if($sesion->estado === 'finalizada')
                                            {{ $sesion->duracion_minutos }} min
                                        @else
                                            <span class="text-yellow-600 dark:text-yellow-500 flex items-center gap-1">
                                                <svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                En vuelo
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($sesion->estado === 'finalizada')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                Completada
                                            </span>
                                        @elseif($sesion->estado === 'activa')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                                                Activa
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center bg-white dark:bg-gray-800">
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <div class="text-4xl mb-4">🛫</div>
                                            <div class="text-lg font-medium">Sin registros de instrucción</div>
                                            <div class="text-sm mt-1">Este instructor aún no ha registrado horas en el simulador.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Paginación --}}
                @if($sesiones->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                        {{ $sesiones->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>