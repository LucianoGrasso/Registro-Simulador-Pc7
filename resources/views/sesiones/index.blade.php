<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 {{ __('Historial de Sesiones') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-700 mb-4">🔍 Filtros de Búsqueda</h5>
                    <form method="GET" action="{{ route('sesiones.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                   value="{{ request('fecha_inicio') }}" style="color: #000 !important; background: #fff !important;">
                        </div>
                        <div class="flex-1 min-w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                   value="{{ request('fecha_fin') }}" style="color: #000 !important; background: #fff !important;">
                        </div>
                        <div class="flex-1 min-w-64">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Alumno</label>
                            <input type="text" name="alumno_buscar" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                   placeholder="Nombre o NPI" value="{{ request('alumno_buscar') }}" style="color: #000 !important; background: #fff !important;">
                        </div>
                        <div class="min-w-40">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select name="estado" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" style="color: #000 !important; background: #fff !important;">
                                <option value="">Todos</option>
                                <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Activa</option>
                                <option value="finalizada" {{ request('estado') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            🔍 Buscar
                        </button>
                        @if(request()->hasAny(['fecha_inicio', 'fecha_fin', 'alumno_buscar', 'estado']))
                            <a href="{{ route('sesiones.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                ↻ Limpiar
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    @if($sesiones->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                        ID
                                    </th>
                                    
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alumno
                                    </th>
                                    
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                        Fecha/Hora
                                    </th>
                                    
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Duración
                                    </th>
                                    
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actividad
                                    </th>
                                    
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sesiones as $sesion)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                            #{{ $sesion->id }}
                                        </td>

                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-xl mr-2">
                                                    @if($sesion->estado === 'activa') 🟡
                                                    @elseif($sesion->estado === 'finalizada') 🟢
                                                    @else 🔴
                                                    @endif
                                                </div>
                                                <div class="overflow-hidden"> <div class="text-sm font-medium text-gray-900 truncate max-w-[150px] lg:max-w-xs" title="{{ $sesion->alumno->nombre_completo }}">
                                                        {{ $sesion->alumno->nombre_completo }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $sesion->npi }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="text-xs text-gray-900 font-semibold">📅 {{ $sesion->fecha->format('d/m/y') }}</div>
                                            <div class="text-xs text-gray-500">
                                                🕐 {{ $sesion->hora_inicio->format('H:i') }}
                                                @if($sesion->hora_fin) - {{ $sesion->hora_fin->format('H:i') }} @endif
                                            </div>
                                        </td>

                                        <td class="px-3 py-4 whitespace-nowrap text-sm">
                                            @if($sesion->duracion_minutos)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                    {{ $sesion->duracion_minutos }} min
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>

                                        <td class="px-3 py-4 whitespace-nowrap">
                                            @if($sesion->estado === 'activa')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Activa</span>
                                            @elseif($sesion->estado === 'finalizada')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Fin</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Cancel</span>
                                            @endif
                                        </td>

                                        <td class="px-3 py-4">
                                            <div class="text-sm text-gray-900" title="{{ $sesion->actividad }}">
                                                {{ Str::limit($sesion->actividad, 50, '...') }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($sesiones->hasPages())
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                            {{ $sesiones->links() }}
                        </div>
                        @endif
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <div class="text-4xl mb-4">📋</div>
                                <div class="text-lg font-medium">No se encontraron sesiones</div>
                                <div class="text-sm mt-2">
                                    Intenta ajustar los filtros de búsqueda
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>