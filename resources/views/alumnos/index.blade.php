<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Alumnos') }}
            </h2>
            <a href="{{ route('alumnos.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center shadow transition-colors">
                <span class="mr-2">➕</span>
                Nuevo Alumno
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" class="flex flex-wrap gap-4 items-end">
                        
                        <div class="flex-1 min-w-64">
                            <label for="buscar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar</label>
                            <input type="text" 
                                id="buscar" 
                                name="buscar" 
                                value="{{ request('buscar') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white dark:placeholder-gray-400 transition-colors"
                                placeholder="Nombre, RUT, NPI o correo...">
                                </div>

                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                            <select id="estado" 
                                    name="estado" 
                                    class="mt-1 block px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                                    <option value="">Todos</option>
                                <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>

                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                            🔍 Buscar
                        </button>
                        <a href="{{ route('alumnos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                            ↻ Limpiar
                        </a>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">🎓</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Alumnos</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $alumnos->total() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">✅</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Activos</p>
                                <p class="text-2xl font-semibold text-green-600 dark:text-green-400">
                                    {{ $alumnos->where('is_active', true)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">⏱️</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Con Sesiones Activas</p>
                                <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-500">
                                    {{ $alumnos->where('sesiones_activas_count', '>', 0)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">❌</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Inactivos</p>
                                <p class="text-2xl font-semibold text-red-600 dark:text-red-400">
                                    {{ $alumnos->where('is_active', false)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Alumno
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    NPI
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Sesiones
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($alumnos as $alumno)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ !$alumno->is_active ? 'opacity-60' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-2xl mr-3">
                                                @if($alumno->sesiones_activas_count > 0)
                                                    🟡
                                                @elseif($alumno->is_active)
                                                    🟢
                                                @else
                                                    🔴
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $alumno->nombre_completo }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $alumno->rut_formateado }}
                                                </div>
                                                @if($alumno->correo)
                                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                                        📧 {{ $alumno->correo }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium font-mono bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            {{ $alumno->npi }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        <div class="flex items-center space-x-4">
                                            <div>
                                                <div class="font-medium dark:text-white">{{ $alumno->sesiones_count ?? 0 }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Total</div>
                                            </div>
                                            @if($alumno->sesiones_activas_count > 0)
                                                <div class="text-yellow-600 dark:text-yellow-500 font-medium">
                                                    <div>{{ $alumno->sesiones_activas_count }}</div>
                                                    <div class="text-xs">Activas</div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($alumno->sesiones_activas_count > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                                🔄 En simulador
                                            </span>
                                        @elseif($alumno->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                ✅ Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                ❌ Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('alumnos.show', $alumno->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md border transition-colors
                                                      bg-red-50 text-red-700 border-red-200 hover:bg-red-100 
                                                      dark:bg-red-900/20 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-900/40" 
                                               title="Ver detalles y QR">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </a>

                                            <a href="{{ route('alumnos.edit', $alumno->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md border transition-colors
                                                      bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100 
                                                      dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800 dark:hover:bg-blue-900/40" 
                                               title="Editar alumno">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </a>

                                            <form method="POST" action="{{ route('alumnos.toggle-estado', $alumno->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md border transition-colors
                                                               {{ $alumno->is_active 
                                                                  ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800 dark:hover:bg-yellow-900/40' 
                                                                  : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800 dark:hover:bg-green-900/40' 
                                                               }}" 
                                                        title="{{ $alumno->is_active ? 'Desactivar alumno' : 'Activar alumno' }}"
                                                        onclick="return confirm('¿Cambiar estado del alumno?')">
                                                    @if($alumno->is_active)
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Pausar
                                                    @else
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-4-8V3m0 3V3m0 0a9 9 0 00-9 9h4a5 5 0 015-5z"></path>
                                                        </svg>
                                                        Activar
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center bg-white dark:bg-gray-800">
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <div class="text-4xl mb-4">🎓</div>
                                            <div class="text-lg font-medium">No hay alumnos registrados</div>
                                            <div class="text-sm mt-2">
                                                <a href="{{ route('alumnos.create') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                                    Crear el primer alumno
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($alumnos->hasPages())
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
                    {{ $alumnos->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>