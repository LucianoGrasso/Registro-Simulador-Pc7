<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Instructores') }}
            </h2>
            <button onclick="toggleFormulario()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center shadow transition-colors">
                <span class="mr-2">➕</span>
                Nuevo Instructor
            </button>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Alertas y Errores --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <strong class="font-bold">¡Error al guardar!</strong>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORMULARIO OCULTO --}}
            <div id="form-nuevo" class="{{ $errors->any() ? '' : 'hidden' }} bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">➕ Agregar Instructor</h3>
                    <button onclick="toggleFormulario()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">✖</button>
                </div>
                <form method="POST" action="{{ route('instructores.store') }}" class="flex flex-wrap gap-4 items-end">
                    @csrf
                    <div class="flex-1 min-w-[250px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NPI</label>
                        <input type="text" name="npi" required placeholder="Ej: 123456-7" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono">
                    </div>
                    <div class="flex-[2] min-w-[250px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grado y Nombre Completo</label>
                        <input type="text" name="grado_nombre" required placeholder="Ej: T1 (NV) Juan Pérez" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition-colors h-[42px]">
                        Guardar
                    </button>
                </form>
            </div>

            {{-- BARRA DE BÚSQUEDA Y FILTROS (Mismo diseño que alumnos) --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg transition-colors duration-300">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[250px]">
                            <label for="buscar" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar</label>
                            <input type="text" id="buscar" name="buscar" value="{{ request('buscar') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white dark:placeholder-gray-400 transition-colors"
                                placeholder="Nombre o NPI...">
                        </div>
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                            <select id="estado" name="estado" class="mt-1 block px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                                <option value="">Todos</option>
                                <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                            🔍 Buscar
                        </button>
                        <a href="{{ route('instructores.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                            ↻ Limpiar
                        </a>
                    </form>
                </div>
            </div>

            {{-- TARJETAS DE ESTADÍSTICAS (KPIs) --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">👨‍✈️</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Instructores</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalInstructores }}</p>
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
                                <p class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ $activos }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg transition-colors">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 text-2xl">⏱️</div>
                            <div class="ml-5">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Horas Impartidas</p>
                                <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-500">{{ $horasGlobales }} <span class="text-sm font-normal">hrs</span></p>
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
                                <p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $inactivos }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABLA --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Instructor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NPI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estadísticas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($instructores as $instructor)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ !$instructor->activo ? 'opacity-60' : '' }}">
                                    
                                    {{-- Nombre y Punto de Estado --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-2xl mr-3">
                                                @if($instructor->activo) 🟢 @else 🔴 @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $instructor->grado_nombre }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- NPI (Pill Style) --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium font-mono bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            {{ $instructor->npi }}
                                        </span>
                                    </td>

                                    {{-- Estadísticas (Sesiones y Horas) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        <div class="flex items-center space-x-4">
                                            <div>
                                                <div class="font-medium dark:text-white">{{ $instructor->sesiones_count ?? 0 }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Sesiones</div>
                                            </div>
                                            @php
                                                $minutosTotales = $instructor->total_minutos ?? 0;
                                                $horas = floor($minutosTotales / 60);
                                                $minutos = $minutosTotales % 60;
                                            @endphp
                                            <div class="text-blue-600 dark:text-blue-400 font-medium">
                                                <div>{{ str_pad($horas, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($minutos, 2, '0', STR_PAD_LEFT) }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Horas acumuladas</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Estado (Pill Style) --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($instructor->activo)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                ✅ Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                ❌ Inactivo
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Acciones (Botones Style) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <form method="POST" action="{{ route('instructores.toggle-estado', $instructor->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md border transition-colors
                                                               {{ $instructor->activo 
                                                                  ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:border-yellow-800 dark:hover:bg-yellow-900/40' 
                                                                  : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800 dark:hover:bg-green-900/40' 
                                                               }}" 
                                                        title="{{ $instructor->activo ? 'Desactivar instructor' : 'Activar instructor' }}"
                                                        onclick="return confirm('¿Cambiar estado del instructor?')">
                                                    @if($instructor->activo)
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Pausar
                                                    @else
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-4-8V3m0 3V3m0 0a9 9 0 00-9 9h4a5 5 0 015-5z"></path></svg>
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
                                            <div class="text-4xl mb-4">👨‍✈️</div>
                                            <div class="text-lg font-medium">No hay instructores registrados</div>
                                            <div class="text-sm mt-2">
                                                <button onclick="toggleFormulario()" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300">
                                                    Crear el primer instructor
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFormulario() {
            const form = document.getElementById('form-nuevo');
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                form.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>