<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Ticket de Soporte
            </h2>
            <a href="{{ route('soporte.mis-tickets') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Ver Mis Tickets
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Reportar Falla o Sugerencia</h3>
                        <p class="text-gray-600 text-sm">
                            Utiliza este formulario para reportar problemas técnicos o enviar sugerencias de mejora para el sistema.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('soporte.store') }}">
                        @csrf

                        <!-- Tipo de Ticket -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Tipo de Ticket *
                            </label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="tipo" value="falla" class="form-radio text-red-600" required checked>
                                    <span class="ml-2 text-gray-600">Falla / Problema</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="tipo" value="sugerencia" class="form-radio text-red-600" required>
                                    <span class="ml-2 text-gray-700">Sugerencia / Mejora</span>
                                </label>
                            </div>
                            @error('tipo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prioridad -->
                        <div class="mb-4">
                            <label for="prioridad" class="block text-gray-700 text-sm font-bold mb-2">
                                Prioridad *
                            </label>
                            <select name="prioridad" id="prioridad" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                <option value="baja">Baja - Puede esperar</option>
                                <option value="media" selected>Media - Importante</option>
                                <option value="alta">Alta - Urgente</option>
                            </select>
                            @error('prioridad')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Título -->
                        <div class="mb-4">
                            <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">
                                Título *
                            </label>
                            <input type="text" name="titulo" id="titulo" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   placeholder="Ej: Error al escanear QR, Sugerencia para mejorar reportes..." 
                                   value="{{ old('titulo') }}" required>
                            @error('titulo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-6">
                            <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">
                                Descripción Detallada *
                            </label>
                            <textarea name="descripcion" id="descripcion" rows="6" 
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                      placeholder="Describe el problema o sugerencia con el mayor detalle posible. Si es una falla, indica los pasos para reproducirla." 
                                      required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">
                                Incluye capturas de pantalla si es necesario (puedes adjuntarlas por otro medio y mencionarlo aquí)
                            </p>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                                Enviar Ticket
                            </button>
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2">Información</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Los tickets son revisados por el administrador del sistema</li>
                    <li>• Recibirás una respuesta en cuanto sea posible</li>
                    <li>• Puedes ver el estado de tus tickets en "Ver Mis Tickets"</li>
                    <li>• Para problemas críticos, contacta directamente al administrador</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>