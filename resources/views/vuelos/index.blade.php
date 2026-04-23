<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('✈️ Historial de Vuelos (Telemetría)') }}
        </h2>
        <style>
            .badge-nuevo {
                background-color: #10b981; color: white; font-size: 0.65rem; padding: 2px 6px;
                border-radius: 4px; margin-left: 8px; font-weight: bold; text-transform: uppercase;
                animation: pulse-green 2s infinite; vertical-align: middle; display: inline-block;
                border: 1px solid #059669;
            }
            @keyframes pulse-green {
                0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
                70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
                100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
            }
            /* Corrección para inputs date en modo oscuro */
            .dark input[type="date"]::-webkit-calendar-picker-indicator {
                filter: invert(1);
            }
        </style>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h5 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">🔍 Filtros de Búsqueda</h5>
                    
                    <form method="GET" action="{{ route('vuelos.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-48">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" 
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200" 
                                   value="{{ request('fecha_inicio') }}">
                        </div>

                        <div class="flex-1 min-w-48">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" 
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200" 
                                   value="{{ request('fecha_fin') }}">
                        </div>

                        <div class="flex-1 min-w-64">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Buscar Vuelo</label>
                            <input type="text" name="search" 
                                   class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200" 
                                   placeholder="Alumno, NPI o Archivo..." 
                                   value="{{ request('search') }}">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150">
                                🔍 Buscar
                            </button>

                            @if(request()->hasAny(['fecha_inicio', 'fecha_fin', 'search']))
                                <a href="{{ route('vuelos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150">
                                    ↻ Limpiar
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    
                    @if(count($vuelos) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Alumno / Piloto</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Fecha y Hora</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Archivo</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900/50 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Peso</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900/50 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vuelos as $vuelo)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                        <p class="text-gray-900 dark:text-white whitespace-no-wrap font-bold">{{ $vuelo['alumno'] }}</p>
                                        @if($vuelo['npi']) <p class="text-gray-400 dark:text-gray-500 text-xs">{{ $vuelo['npi'] }}</p> @endif
                                    </td>
                                    
                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                        <div class="flex items-center">
                                            <p class="text-gray-900 dark:text-gray-300 whitespace-no-wrap">{{ $vuelo['fecha_texto'] }} hrs</p>
                                            @if(isset($archivoMasReciente) && $vuelo['archivo'] == $archivoMasReciente && !request()->hasAny(['search', 'fecha_inicio']))
                                                <span class="badge-nuevo">¡NUEVO!</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                        <p class="text-gray-600 dark:text-gray-400 whitespace-no-wrap text-xs font-mono">{{ $vuelo['archivo'] }}</p>
                                    </td>

                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                        <span class="relative inline-block px-3 py-1 font-semibold text-blue-900 dark:text-blue-200 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-blue-100 dark:bg-blue-900 opacity-50 rounded-full"></span>
                                            <span class="relative">{{ $vuelo['size'] }}</span>
                                        </span>
                                    </td>

                                    <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('vuelos.show', $vuelo['archivo']) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                                Mapa 🗺️
                                            </a>
                                            
                                            <a href="{{ route('idu.reproductor', $vuelo['archivo']) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none transition ease-in-out duration-150 shadow-sm font-bold">
                                                IDU ▶️
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4 px-4 py-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                            {{ $vuelos->links() }}
                        </div>
                        
                    </div>
                    @else
                        <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                            <p class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-200">No se encontraron vuelos</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>