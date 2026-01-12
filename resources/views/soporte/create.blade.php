<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Crear Ticket de Soporte
            </h2>
            <a href="{{ route('soporte.mis-tickets') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                Ver Mis Tickets
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded transition-colors">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border dark:border-gray-700 transition-colors">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Reportar Falla o Sugerencia</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Utiliza este formulario para reportar problemas técnicos o enviar sugerencias de mejora para el sistema.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('soporte.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Tipo de Ticket *
                            </label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="tipo" value="falla" class="form-radio text-red-600 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600" required checked>
                                    <span class="ml-2 text-gray-600 dark:text-gray-300">Falla / Problema</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="tipo" value="sugerencia" class="form-radio text-red-600 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600" required>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Sugerencia / Mejora</span>
                                </label>
                            </div>
                            @error('tipo')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="prioridad" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Prioridad *
                            </label>
                            <select name="prioridad" id="prioridad" class="shadow border border-gray-300 dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" required>
                                <option value="baja">Baja - Puede esperar</option>
                                <option value="media" selected>Media - Importante</option>
                                <option value="alta">Alta - Urgente</option>
                            </select>
                            @error('prioridad')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="titulo" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Título *
                            </label>
                            <input type="text" name="titulo" id="titulo" 
                                   class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" 
                                   placeholder="Ej: Error al escanear QR, Sugerencia para mejorar reportes..." 
                                   value="{{ old('titulo') }}" required>
                            @error('titulo')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="descripcion" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Descripción Detallada *
                            </label>
                            <textarea name="descripcion" id="descripcion" rows="6" 
                                      class="shadow appearance-none border border-gray-300 dark:border-gray-600 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" 
                                      placeholder="Describe el problema o sugerencia con el mayor detalle posible. Si es una falla, indica los pasos para reproducirla." 
                                      required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                                Incluye capturas de pantalla si es necesario (puedes adjuntarlas por otro medio y mencionarlo aquí)
                            </p>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition-transform transform hover:scale-105">
                                Enviar Ticket
                            </button>
                            <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 transition-colors">
                <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">Información</h4>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                    <li>• Los tickets son revisados por el administrador del sistema</li>
                    <li>• Recibirás una respuesta en cuanto sea posible</li>
                    <li>• Puedes ver el estado de tus tickets en "Ver Mis Tickets"</li>
                    <li>• Para problemas críticos, contacta directamente al administrador</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>