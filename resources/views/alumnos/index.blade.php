<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Alumnos') }}
            </h2>
            <a href="{{ route('alumnos.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <span class="mr-2">➕</span>
                Nuevo Alumno
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filtros de búsqueda -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-64">
                            <label for="buscar" class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input type="text" 
                                   id="buscar" 
                                   name="buscar" 
                                   value="{{ request('buscar') }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Nombre, RUT, NPI o correo..."
                                   style="color: #000 !important; background: #fff !important;">
                        </div>
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select id="estado" 
                                    name="estado" 
                                    class="mt-1 block px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    style="color: #000 !important; background: #fff !important;">
                                <option value="">Todos</option>
                                <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            🔍 Buscar
                        </button>
                        <a href="{{ route('alumnos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            ↻ Limpiar
                        </a>
                    </form>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">🎓</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500">Total Alumnos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $alumnos->total() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">✅</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500">Activos</p>
                                <p class="text-2xl font-semibold text-green-600">
                                    {{ $alumnos->where('is_active', true)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">⏱️</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500">Con Sesiones Activas</p>
                                <p class="text-2xl font-semibold text-yellow-600">
                                    {{ $alumnos->where('sesiones_activas_count', '>', 0)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">❌</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500">Inactivos</p>
                                <p class="text-2xl font-semibold text-red-600">
                                    {{ $alumnos->where('is_active', false)->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de alumnos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alumno
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    NPI
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sesiones
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($alumnos as $alumno)
                                <tr class="hover:bg-gray-50 {{ !$alumno->is_active ? 'opacity-60' : '' }}">
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
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $alumno->nombre_completo }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $alumno->rut_formateado }}
                                                </div>
                                                @if($alumno->correo)
                                                    <div class="text-xs text-gray-400">
                                                        📧 {{ $alumno->correo }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium font-mono bg-gray-100 text-gray-800">
                                            {{ $alumno->npi }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center space-x-4">
                                            <div>
                                                <div class="font-medium">{{ $alumno->sesiones_count ?? 0 }}</div>
                                                <div class="text-xs text-gray-500">Total</div>
                                            </div>
                                            @if($alumno->sesiones_activas_count > 0)
                                                <div class="text-yellow-600 font-medium">
                                                    <div>{{ $alumno->sesiones_activas_count }}</div>
                                                    <div class="text-xs">Activas</div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($alumno->sesiones_activas_count > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                🔄 En simulador
                                            </span>
                                        @elseif($alumno->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✅ Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ❌ Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <!-- Ver detalles -->
                                            <a href="{{ route('alumnos.show', $alumno->id) }}" 
                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-md border border-red-200 hover:bg-red-100 hover:border-red-300 transition-colors" 
                                            style="background-color: #fef2f2; color: #dc2626; border-color: #fecaca;"
                                            title="Ver detalles y QR">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </a>

                                            <!-- Editar -->
                                            <a href="{{ route('alumnos.edit', $alumno->id) }}" 
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-md border border-blue-200 hover:bg-blue-100 hover:border-blue-300 transition-colors" 
                                            title="Editar alumno">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </a>

                                            <!-- Activar/Desactivar -->
                                            <form method="POST" action="{{ route('alumnos.toggle-estado', $alumno->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 {{ $alumno->is_active ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100 hover:border-yellow-300' : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100 hover:border-green-300' }} text-xs font-medium rounded-md border transition-colors" 
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

                                            <!-- Eliminar (solo si no tiene sesiones activas) -->
                                            @if($alumno->sesiones_activas_count == 0)
                                                <form method="POST" action="{{ route('alumnos.destroy', $alumno->id) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-md border border-red-200 hover:bg-red-100 hover:border-red-300 transition-colors" 
                                                            title="Eliminar alumno"
                                                            onclick="return confirm('¿Estás seguro de eliminar este alumno? Esta acción no se puede deshacer.')">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Eliminar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <div class="text-4xl mb-4">🎓</div>
                                            <div class="text-lg font-medium">No hay alumnos registrados</div>
                                            <div class="text-sm mt-2">
                                                <a href="{{ route('alumnos.create') }}" class="text-blue-600 hover:text-blue-900">
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

                <!-- Paginación -->
                @if($alumnos->hasPages())
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                    {{ $alumnos->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>