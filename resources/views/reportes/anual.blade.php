<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    📊 Reporte Anual - {{ $stats['año'] }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Resumen general y métricas del año completo</p>
            </div>
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('reportes.anual') }}" class="flex items-center space-x-2">
                    <select name="año" class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md px-4 py-2 text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @for($a = now()->year; $a >= now()->year - 3; $a--)
                            <option value="{{ $a }}" {{ $stats['año'] == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Ver Año
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
                        📅 Actividad Mensual del Año
                    </h3>
                    <div class="h-96 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/50 flex items-end p-4 gap-2">
                        @php
                            $maxSesiones = max(array_values($sesionesPorMes)) ?: 1;
                        @endphp
                        
                        @foreach($sesionesPorMes as $mes => $sesiones)
                            @php
                                $porcentaje = $maxSesiones > 0 ? ($sesiones / $maxSesiones) * 100 : 0;
                            @endphp
                            <div class="flex flex-col items-center justify-end flex-1 h-full group">
                                @if($sesiones > 0)
                                    <div class="text-xs font-bold text-gray-600 dark:text-gray-300 mb-2 opacity-0 group-hover:opacity-100 transition-opacity">{{ $sesiones }}</div>
                                    <div class="w-full bg-blue-500 dark:bg-blue-600 rounded-t-sm transition-all duration-500 hover:bg-blue-400 dark:hover:bg-blue-500" 
                                         style="height: {{ max($porcentaje, 2) }}%;"></div>
                                @else
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-t-sm h-1"></div>
                                @endif
                                <div class="mt-2 text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400 text-center uppercase">
                                    {{ $mes }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 transition-colors">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Rendimiento</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Promedio Mensual</span>
                                <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $stats['promedio_mensual'] }} ses.</span>
                            </div>
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Duración Promedio</span>
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">{{ $stats['tiempo_promedio'] }} min</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-4 transition-colors">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">
                            📚 Pruebas Syllabus Realizadas
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
                        <span class="text-xs text-gray-500 dark:text-gray-400">(Top {{ $topAlumnosAño->count() }})</span>
                    </h3>
                    @if($topAlumnosAño->count() > 0)
                        <div class="space-y-2">
                            @foreach($topAlumnosAño as $index => $alumnoData)
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
                    <h3 class="text-base font-medium text-gray-900 dark:text-white mb-3">Análisis por Trimestres</h3>
                    @php
                        $trimestres = [
                            'Q1 (Ene-Mar)' => 0,
                            'Q2 (Abr-Jun)' => 0,
                            'Q3 (Jul-Sep)' => 0,
                            'Q4 (Oct-Dic)' => 0,
                        ];
                        
                        $mesNum = 1;
                        foreach ($sesionesPorMes as $mes => $cantidad) {
                            if ($mesNum <= 3) $trimestres['Q1 (Ene-Mar)'] += $cantidad;
                            elseif ($mesNum <= 6) $trimestres['Q2 (Abr-Jun)'] += $cantidad;
                            elseif ($mesNum <= 9) $trimestres['Q3 (Jul-Sep)'] += $cantidad;
                            else $trimestres['Q4 (Oct-Dic)'] += $cantidad;
                            $mesNum++;
                        }
                    @endphp
                    
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($trimestres as $nombreQ => $cantidad)
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg text-center border border-blue-100 dark:border-blue-800/30 flex flex-col justify-center">
                                <div class="text-xs font-medium text-blue-700 dark:text-blue-400 mb-1">{{ $nombreQ }}</div>
                                <div class="text-3xl font-bold text-blue-900 dark:text-blue-300">{{ $cantidad }}</div>
                                <div class="text-xs text-blue-600 dark:text-blue-500 mt-1">Sesiones</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>