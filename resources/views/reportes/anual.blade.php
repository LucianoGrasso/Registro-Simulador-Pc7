<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    📊 Reporte Anual - {{ $stats['año'] }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Análisis completo de la actividad anual del simulador</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('reportes.anual') }}" class="flex items-center space-x-2">
                    <select name="año" class="border border-gray-300 rounded-md px-4 py-2 text-sm">
                        @for($a = now()->year; $a >= now()->year - 5; $a--)
                            <option value="{{ $a }}" {{ $stats['año'] == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                        Ver Año
                    </button>
                </form>
                <a href="{{ route('reportes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            
            <!-- Resumen Ejecutivo Anual -->
            <!-- Mantenemos md:grid-cols-4 porque seguimos teniendo 4 tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <!-- 1. Total Sesiones -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs font-medium text-gray-500">Total Sesiones</div>
                            <div class="text-xl font-bold text-gray-900">{{ $stats['total_sesiones'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- 2. Alumnos (Antes era la 3ra, ahora sube al puesto 2) -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs font-medium text-gray-500">Alumnos Activos</div>
                            <div class="text-xl font-bold text-gray-900">{{ $stats['alumnos_activos'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- 3. Tiempo Total -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs font-medium text-gray-500">Tiempo Total</div>
                            <div class="text-xl font-bold text-gray-900">{{ $stats['tiempo_total_horas'] }}h</div>
                        </div>
                    </div>
                </div>

                <!-- 4. AHORRO ESTIMADO (Reemplaza a Finalizadas) -->
                @php
                    $costoHora = 650;
                    $horas = floatval($stats['tiempo_total_horas']);
                    $ahorro = $horas * $costoHora;
                @endphp
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="flex items-center">
                        <!-- Icono Dinero (Verde Esmeralda suave) -->
                        <div class="p-2 rounded-full bg-green-100 text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs font-medium text-gray-500">Ahorro Estimado</div>
                            <div class="text-xl font-bold text-gray-900">
                                ${{ number_format($ahorro, 0, ',', '.') }} <span class="text-xs font-normal text-gray-400">USD</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Métricas Anuales -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-base font-medium text-gray-900 mb-3">Métricas del Año {{ $stats['año'] }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-xl font-bold text-blue-600">{{ $stats['promedio_mensual'] }}</div>
                        <div class="text-xs text-gray-500">Promedio Mensual de Sesiones</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-green-600">{{ $stats['tiempo_promedio'] }} min</div>
                        <div class="text-xs text-gray-500">Duración Promedio</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-purple-600">{{ round($stats['tiempo_total_horas'] / 12, 1) }}h</div>
                        <div class="text-xs text-gray-500">Promedio Mensual de Horas</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Actividad Mensual del Año -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-base font-medium text-gray-900 mb-3">
                        📅 Actividad Mensual del Año 
                        <span class="text-xs text-gray-500">(Scroll para ver más)</span>
                    </h3>
                    <div class="h-96 overflow-y-scroll border border-gray-200 rounded bg-gray-50" style="max-height: 384px;">
                        @php
                            $maxSesionesMes = max(array_values($sesionesPorMes)) ?: 1;
                        @endphp
                        <div class="p-3">
                            @foreach($sesionesPorMes as $nombreMes => $sesiones)
                                @php
                                    $porcentaje = $maxSesionesMes > 0 ? ($sesiones / $maxSesionesMes) * 100 : 0;
                                @endphp
                                <div class="flex items-center space-x-3 mb-3 p-3 bg-white rounded shadow border-l-4 {{ $sesiones > 0 ? 'border-blue-500' : 'border-gray-300' }}">
                                    <div class="w-20 text-center flex-shrink-0">
                                        <div class="text-sm font-bold text-gray-800">{{ $nombreMes }}</div>
                                        <div class="text-xs text-gray-500">{{ $stats['año'] }}</div>
                                    </div>
                                    <div class="flex-1 bg-gray-200 rounded h-6 overflow-hidden">
                                        @if($sesiones > 0)
                                            <div class="bg-blue-500 h-6 rounded flex items-center justify-center text-white text-sm font-bold" 
                                                 style="width: {{ max($porcentaje, 40) }}%;">
                                                {{ $sesiones }}
                                            </div>
                                        @else
                                            <div class="bg-gray-300 h-6 rounded flex items-center justify-center">
                                                <span class="text-gray-600 text-sm">0</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600 w-12 text-right font-semibold">
                                        {{ $sesiones }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Alumnos del Año -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-base font-medium text-gray-900 mb-3">
                        🏆 Top Alumnos del Año
                        <span class="text-xs text-gray-500">(Top {{ $topAlumnosAño->count() }})</span>
                    </h3>
                    @if($topAlumnosAño->count() > 0)
                        <div class="h-96 overflow-y-auto border border-gray-100 rounded p-2 bg-gray-50" style="max-height: 384px;">
                            <div class="space-y-2">
                                @foreach($topAlumnosAño as $index => $alumnoData)
                                    <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm border">
                                        <div class="flex items-center min-w-0">
                                            <div class="text-lg mr-3 flex-shrink-0 w-8 flex justify-center">
                                                @if($loop->iteration === 1) 🥇
                                                @elseif($loop->iteration === 2) 🥈
                                                @elseif($loop->iteration === 3) 🥉
                                                @else 
                                                    <span class="bg-gray-200 text-gray-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">{{ $loop->iteration }}</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $alumnoData['alumno']->nombre_completo }}
                                                </div>
                                                <div class="text-xs text-gray-600">
                                                    NPI: {{ $alumnoData['alumno']->npi }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right flex-shrink-0 ml-4">
                                            <div class="text-lg font-bold text-blue-600">
                                                {{ $alumnoData['total_sesiones'] }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ round($alumnoData['tiempo_total'] / 60, 1) }}h total
                                            </div>
                                            @if($alumnoData['promedio_duracion'])
                                                <div class="text-xs text-gray-400">
                                                    {{ $alumnoData['promedio_duracion'] }}min prom.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-8">
                            <div class="text-4xl mb-2">📊</div>
                            <div>No hay actividad registrada</div>
                            <div class="text-sm">para este año</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Análisis por Trimestres -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-base font-medium text-gray-900 mb-3">📈 Análisis por Trimestres</h3>
                @php
                    $trimestres = [
                        'T1' => ['meses' => ['Enero', 'Febrero', 'Marzo'], 'sesiones' => 0, 'periodo' => 'Ene-Mar'],
                        'T2' => ['meses' => ['Abril', 'Mayo', 'Junio'], 'sesiones' => 0, 'periodo' => 'Abr-Jun'],
                        'T3' => ['meses' => ['Julio', 'Agosto', 'Septiembre'], 'sesiones' => 0, 'periodo' => 'Jul-Sep'],
                        'T4' => ['meses' => ['Octubre', 'Noviembre', 'Diciembre'], 'sesiones' => 0, 'periodo' => 'Oct-Dic']
                    ];
                    
                    // Calcular sesiones por trimestre
                    foreach($trimestres as $t => $trimestre) {
                        foreach($trimestre['meses'] as $mes) {
                            $trimestres[$t]['sesiones'] += $sesionesPorMes[$mes] ?? 0;
                        }
                    }
                @endphp
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($trimestres as $nombreTrimestre => $datosTrimestre)
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg text-center border border-blue-200">
                            <div class="text-sm font-medium text-blue-700 mb-1">{{ $nombreTrimestre }}</div>
                            <br>
                            <div class="text-2xl font-bold text-blue-900 mb-1">{{ $datosTrimestre['sesiones'] }}</div>
                            <div class="text-xs text-blue-400">Sesiones</div>
                            <br>
                            <div class="text-xs text-blue-600">
                                {{ $datosTrimestre['periodo'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Comparación con años anteriores -->
            @if($stats['año'] > (now()->year - 3))
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-base font-medium text-gray-900 mb-3">📊 Tendencia Anual</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @for($yearCompare = $stats['año']; $yearCompare >= max($stats['año'] - 3, now()->year - 5); $yearCompare--)
                            @php
                                $yearSessions = 0;
                                if($yearCompare == $stats['año']) {
                                    $yearSessions = $stats['total_sesiones'];
                                }
                            @endphp
                            <div class="bg-gray-50 p-3 rounded text-center {{ $yearCompare == $stats['año'] ? 'bg-blue-50 border-2 border-blue-200' : '' }}">
                                <div class="text-sm font-medium {{ $yearCompare == $stats['año'] ? 'text-blue-700' : 'text-gray-700' }}">
                                    {{ $yearCompare }}
                                </div>
                                <div class="text-lg font-bold {{ $yearCompare == $stats['año'] ? 'text-blue-900' : 'text-gray-900' }}">
                                    {{ $yearCompare == $stats['año'] ? $stats['total_sesiones'] : '-' }}
                                    <div class="text-xs text-blue-400">Sesiones</div>
                                </div>
                                <div class="text-xs {{ $yearCompare == $stats['año'] ? 'text-blue-600' : 'text-gray-500' }}">
                                    {{ $yearCompare == $stats['año'] ? 'Año actual' : 'Datos no disponibles' }}
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>