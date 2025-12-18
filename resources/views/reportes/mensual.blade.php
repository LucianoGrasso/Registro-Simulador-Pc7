<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    📈 Reporte Mensual - {{ $stats['periodo'] }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Análisis detallado de la actividad mensual del simulador</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('reportes.mensual') }}" class="flex items-center space-x-2">
                    <select name="mes" class="border border-gray-300 rounded-md px-4 py-2 text-sm">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $stats['mes'] == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->locale('es')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                    <select name="año" class="border border-gray-300 rounded-md px- py-2 text-sm">
                        @for($a = now()->year; $a >= now()->year - 3; $a--)
                            <option value="{{ $a }}" {{ $stats['año'] == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                        Ver Período
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
            
            <!-- Resumen Ejecutivo -->
            <!-- Mantenemos la grilla de 4 columnas -->
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

                <!-- 2. Alumnos Activos -->
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

            <!-- Estadísticas Adicionales -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-base font-medium text-gray-900 mb-3">Métricas del Período</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-xl font-bold text-blue-600">{{ $stats['promedio_diario'] }}</div>
                        <div class="text-xs text-gray-500">Promedio Diario de Sesiones</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-green-600">{{ $stats['tiempo_promedio'] }} min</div>
                        <div class="text-xs text-gray-500">Duración Promedio</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold text-purple-600">
                            @if($crecimiento >= 0)
                                +{{ $crecimiento }}%
                            @else
                                {{ $crecimiento }}%
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">Crecimiento vs Mes Anterior</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Gráfico de Sesiones por Día -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-base font-medium text-gray-900 mb-3">
                        📅 Actividad Diaria del Mes 
                        <span class="text-xs text-gray-500">(Scroll para ver más)</span>
                    </h3>
                    <div class="h-96 overflow-y-scroll border border-gray-200 rounded bg-gray-50" style="max-height: 384px;">
                        @php
                            $maxSesiones = max(array_values($sesionesPorDia)) ?: 1;
                        @endphp
                        <div class="p-3">
                            @foreach($sesionesPorDia as $dia => $sesiones)
                                @php
                                    $porcentaje = $maxSesiones > 0 ? ($sesiones / $maxSesiones) * 100 : 0;
                                    $fecha = \Carbon\Carbon::create($stats['año'], $stats['mes'], $dia);
                                    $esFuturo = $fecha->isFuture();
                                @endphp
                                <div class="flex items-center space-x-3 mb-3 p-3 bg-white rounded shadow border-l-4 {{ $sesiones > 0 && !$esFuturo ? 'border-blue-500' : 'border-gray-300' }}">
                                    <div class="w-14 text-center flex-shrink-0">
                                        <div class="text-lg font-bold text-gray-800">{{ $dia }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $fecha->locale('es')->isoFormat('ddd') }}
                                        </div>
                                    </div>
                                    <div class="flex-1 bg-gray-200 rounded h-6 overflow-hidden">
                                        @if(!$esFuturo && $sesiones > 0)
                                            <div class="bg-blue-500 h-6 rounded flex items-center justify-center text-white text-sm font-bold" 
                                                 style="width: {{ max($porcentaje, 40) }}%;">
                                                {{ $sesiones }}
                                            </div>
                                        @elseif(!$esFuturo)
                                            <div class="bg-gray-300 h-6 rounded flex items-center justify-center">
                                                <span class="text-gray-600 text-sm">0</span>
                                            </div>
                                        @else
                                            <div class="bg-gray-200 h-6 rounded flex items-center justify-center border border-dashed border-gray-400">
                                                <span class="text-gray-400 text-sm">-</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600 w-10 text-right font-semibold">
                                        @if(!$esFuturo && $sesiones > 0)
                                            {{ $sesiones }}
                                        @elseif(!$esFuturo)
                                            0
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Alumnos del Mes -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-base font-medium text-gray-900 mb-3">
                        Ranking de Alumnos
                        <span class="text-xs text-gray-500">(Top {{ $topAlumnosMes->count() }})</span>
                    </h3>
                    @if($topAlumnosMes->count() > 0)
                        <div class="h-96 overflow-y-auto border border-gray-100 rounded p-2 bg-gray-50">
                            <div class="space-y-2">
                                @foreach($topAlumnosMes as $index => $alumnoData)
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
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-8">
                            <div class="text-4xl mb-2">📊</div>
                            <div>No hay actividad registrada</div>
                            <div class="text-sm">para este período</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Resumen por Semanas -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-base font-medium text-gray-900 mb-3">Análisis Semanal</h3>
                @php
                    $fechaInicio = \Carbon\Carbon::create($stats['año'], $stats['mes'], 1);
                    $fechaFin = $fechaInicio->copy()->endOfMonth();
                    $semanas = [];
                    $semanaActual = 1;
                    
                    for ($dia = 1; $dia <= $fechaInicio->daysInMonth; $dia++) {
                        $fecha = \Carbon\Carbon::create($stats['año'], $stats['mes'], $dia);
                        $numeroSemana = $fecha->weekOfMonth;
                        
                        if (!isset($semanas[$numeroSemana])) {
                            $semanas[$numeroSemana] = [
                                'sesiones' => 0,
                                'dias' => []
                            ];
                        }
                        
                        $semanas[$numeroSemana]['sesiones'] += $sesionesPorDia[$dia];
                        $semanas[$numeroSemana]['dias'][] = $dia;
                    }
                @endphp
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($semanas as $numSemana => $semanaData)
                        <div class="bg-blue-50 p-3 rounded text-center">
                            <div class="text-xs font-medium text-blue-700">Semana {{ $numSemana }}</div>
                            <div class="text-lg font-bold text-blue-900">{{ $semanaData['sesiones'] }}</div>
                            <div class="text-xs text-blue-600">
                                {{ min($semanaData['dias']) }}-{{ max($semanaData['dias']) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>