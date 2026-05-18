<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    📈 Reporte Mensual - {{ $stats['periodo'] }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Análisis detallado de la actividad mensual del simulador</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('reportes.mensual') }}" class="flex items-center space-x-2">
                    <select name="mes" class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md px-4 py-2 text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $stats['mes'] == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->locale('es')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                    <select name="año" class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md px-4 py-2 text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @for($a = now()->year; $a >= now()->year - 3; $a--)
                            <option value="{{ $a }}" {{ $stats['año'] == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Ver Período
                    </button>
                </form>
                <a href="{{ route('reportes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Sesiones Totales</div>
                            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['total_sesiones'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Alumnos</div>
                            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['alumnos_activos'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Horas Totales</div>
                            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['tiempo_total_horas'] }}h</div>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-50 dark:bg-orange-900/10 p-4 rounded-lg shadow-sm border border-orange-100 dark:border-orange-800/30 transition-colors">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-orange-500 text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs font-medium text-orange-600 dark:text-orange-400">Instrucción</div>
                            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['sesiones_instruccion'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50 dark:bg-indigo-900/10 p-4 rounded-lg shadow-sm border border-indigo-100 dark:border-indigo-800/30 transition-colors">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-indigo-500 text-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs font-medium text-indigo-600 dark:text-indigo-400">Horas Oficiales</div>
                            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $stats['horas_instruccion'] }}h</div>
                        </div>
                    </div>
                </div>

                @php
                    $costoHora = 650;
                    $horas = floatval($stats['tiempo_total_horas']);
                    $ahorro = $horas * $costoHora;
                @endphp
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-3">
                            <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Ahorro Est.</div>
                            <div class="text-xl font-bold text-gray-900 dark:text-white">
                                ${{ number_format($ahorro, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 transition-colors">
                    <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">
                        📅 Actividad Diaria del Mes 
                        <span class="text-xs text-gray-500 dark:text-gray-400">(Scroll para ver más)</span>
                    </h3>
                    <div class="h-96 overflow-y-scroll border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/50" style="max-height: 384px;">
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
                                <div class="flex items-center space-x-3 mb-3 p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm border-l-4 {{ $sesiones > 0 && !$esFuturo ? 'border-blue-500 dark:border-blue-400' : 'border-gray-300 dark:border-gray-600' }} border-y border-r border-gray-100 dark:border-gray-700">
                                    <div class="w-14 text-center flex-shrink-0">
                                        <div class="text-lg font-bold text-gray-800 dark:text-white">{{ $dia }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $fecha->locale('es')->isoFormat('ddd') }}
                                        </div>
                                    </div>
                                    <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded h-6 overflow-hidden">
                                        @if(!$esFuturo && $sesiones > 0)
                                            <div class="bg-blue-500 dark:bg-blue-600 h-6 rounded flex items-center justify-center text-white text-sm font-bold" 
                                                 style="width: {{ max($porcentaje, 40) }}%;">
                                                {{ $sesiones }}
                                            </div>
                                        @elseif(!$esFuturo)
                                            <div class="bg-gray-300 dark:bg-gray-600 h-6 rounded flex items-center justify-center">
                                                <span class="text-gray-600 dark:text-gray-300 text-sm">0</span>
                                            </div>
                                        @else
                                            <div class="bg-gray-200 dark:bg-gray-800 h-6 rounded flex items-center justify-center border border-dashed border-gray-400 dark:border-gray-600">
                                                <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 transition-colors">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Rendimiento</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Promedio Diario</span>
                                <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $stats['promedio_diario'] }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Duración Promedio</span>
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">{{ $stats['tiempo_promedio'] }} min</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Crecimiento Mensual</span>
                                <span class="text-sm font-bold text-purple-600 dark:text-purple-400">
                                    {{ $crecimiento >= 0 ? '+' : '' }}{{ $crecimiento }}%
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 transition-colors">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">
                            📚 Pruebas evaluadas Realizadas
                        </h3>
                        @if($topPruebas->count() > 0)
                            <div class="space-y-2">
                                @foreach($topPruebas as $prueba)
                                    <div class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-900/50 rounded border border-gray-100 dark:border-gray-700">
                                        <span class="font-mono text-sm font-bold text-indigo-700 dark:text-indigo-400 bg-indigo-100 dark:bg-indigo-900/30 px-2 py-1 rounded">
                                            {{ $prueba['codigo'] }}
                                        </span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $prueba['cantidad'] }} {{ $prueba['cantidad'] == 1 ? 'vez' : 'veces' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                                <div class="text-sm">Sin pruebas oficiales registradas</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 transition-colors">
                    <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">
                        Ranking de Alumnos
                        <span class="text-xs text-gray-500 dark:text-gray-400">(Top {{ $topAlumnosMes->count() }})</span>
                    </h3>
                    @if($topAlumnosMes->count() > 0)
                        <div class="space-y-2">
                            @foreach($topAlumnosMes as $index => $alumnoData)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center min-w-0">
                                        <div class="text-lg mr-3 flex-shrink-0 w-8 flex justify-center">
                                            @if($loop->iteration === 1) 🥇
                                            @elseif($loop->iteration === 2) 🥈
                                            @elseif($loop->iteration === 3) 🥉
                                            @else 
                                                <span class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">{{ $loop->iteration }}</span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $alumnoData['alumno']->nombre_completo ?? 'Desconocido' }}
                                            </div>
                                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ round($alumnoData['tiempo_total'] / 60, 1) }}h total
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0 ml-4 text-sm font-bold text-blue-600 dark:text-blue-400">
                                        {{ $alumnoData['total_sesiones'] }} ses.
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                            <div>No hay actividad registrada</div>
                        </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 transition-colors">
                    <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Análisis Semanal</h3>
                    @php
                        $fechaInicio = \Carbon\Carbon::create($stats['año'], $stats['mes'], 1);
                        $fechaFin = $fechaInicio->copy()->endOfMonth();
                        $semanas = [];
                        
                        for ($dia = 1; $dia <= $fechaInicio->daysInMonth; $dia++) {
                            $fecha = \Carbon\Carbon::create($stats['año'], $stats['mes'], $dia);
                            $numeroSemana = $fecha->weekOfMonth;
                            
                            if (!isset($semanas[$numeroSemana])) {
                                $semanas[$numeroSemana] = ['sesiones' => 0, 'dias' => []];
                            }
                            $semanas[$numeroSemana]['sesiones'] += $sesionesPorDia[$dia];
                            $semanas[$numeroSemana]['dias'][] = $dia;
                        }
                    @endphp
                    
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($semanas as $numSemana => $semanaData)
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg text-center border border-blue-100 dark:border-blue-800/30 flex flex-col justify-center">
                                <div class="text-xs font-medium text-blue-700 dark:text-blue-400 mb-1">Semana {{ $numSemana }}</div>
                                <div class="text-2xl font-bold text-blue-900 dark:text-blue-300">{{ $semanaData['sesiones'] }}</div>
                                <div class="text-xs text-blue-600 dark:text-blue-500 mt-1">
                                    Días: {{ min($semanaData['dias']) }} al {{ max($semanaData['dias']) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>