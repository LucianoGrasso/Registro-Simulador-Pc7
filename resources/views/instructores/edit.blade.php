<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Instructor') }}
            </h2>
            <a href="{{ route('instructores.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow transition-colors text-sm">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Errores de Validación --}}
            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 transition-colors duration-300">
                <div class="p-6">
                    <form method="POST" action="{{ route('instructores.update', $instructor->id) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        {{-- Primera Fila: Grado y Nombre (Ocupa todo el ancho) --}}
                        <div>
                            <label for="grado_nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grado y Nombre Completo</label>
                            <input type="text" id="grado_nombre" name="grado_nombre" value="{{ old('grado_nombre', $instructor->grado_nombre) }}" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors"
                                   placeholder="Ej: T1 (NV) Juan Pérez">
                        </div>

                        {{-- Segunda Fila: Dividida en 2 columnas para aprovechar el espacio --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- NPI --}}
                            <div>
                                <label for="npi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NPI</label>
                                <input type="text" id="npi" name="npi" value="{{ old('npi', $instructor->npi) }}" required maxlength="8"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono transition-colors"
                                       placeholder="123456-7">
                            </div>

                            {{-- PIN --}}
                            <div>
                                <label for="pin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">PIN Secreto (4 dígitos)</label>
                                <input type="text" id="pin" name="pin" value="{{ old('pin', $instructor->pin) }}" required maxlength="4"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono transition-colors"
                                       placeholder="1234">
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('instructores.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition-colors">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>