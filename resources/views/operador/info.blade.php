<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Información del Operador
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Volver al Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Permisos y restricciones -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                        <span class="text-2xl mr-3">🛡️</span>
                        Permisos de Operador
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-blue-800 mb-3">✅ Puedes hacer:</h4>
                            <ul class="space-y-2 text-sm text-blue-700">
                                <li class="flex items-center">
                                    <span class="text-green-500 mr-2">•</span>
                                    Registrar sesiones con scanner QR
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-500 mr-2">•</span>
                                    Ver sesiones activas en tiempo real
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-500 mr-2">•</span>
                                    Ver estadísticas del día
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-500 mr-2">•</span>
                                    Ver historial de sesiones del día
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-500 mr-2">•</span>
                                    Acceder al dashboard informativo
                                </li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-blue-800 mb-3">❌ No puedes hacer:</h4>
                            <ul class="space-y-2 text-sm text-blue-700">
                                <li class="flex items-center">
                                    <span class="text-red-500 mr-2">•</span>
                                    Crear, editar o eliminar alumnos
                                </li>
                                <li class="flex items-center">
                                    <span class="text-red-500 mr-2">•</span>
                                    Generar reportes completos
                                </li>
                                <li class="flex items-center">
                                    <span class="text-red-500 mr-2">•</span>
                                    Cambiar configuración de cuenta
                                </li>
                                <li class="flex items-center">
                                    <span class="text-red-500 mr-2">•</span>
                                    Acceder a funciones administrativas
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas personales -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <span class="text-2xl mr-3">📊</span>
                        Tus Estadísticas
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">
                                {{ auth()->user()->sesionesIniciadas()->count() }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Sesiones Iniciadas</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">
                                {{ auth()->user()->sesionesFinalizadas()->count() }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Sesiones Finalizadas</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">
                                {{ auth()->user()->sesionesIniciadas()->whereDate('created_at', today())->count() }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Sesiones Hoy</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacto con administrador -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-yellow-900 mb-4 flex items-center">
                        <span class="text-2xl mr-3">📞</span>
                        ¿Necesitas ayuda?
                    </h3>
                    
                    <div class="text-sm text-yellow-800">
                        <p class="mb-3">
                            Si necesitas realizar acciones administrativas, 
                            contacta al Encargado del Simulador - Luciano Grasso.
                        </p>
                        
                        <div class="bg-yellow-100 rounded p-3">
                            <p class="font-medium">Solicitudes comunes:</p>
                            <ul class="mt-2 space-y-1">
                                <li>• Actualización de información personal</li>
                                <li>• Problemas con el scanner</li>
                                <li>• Dudas sobre el sistema</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón para volver -->
            <div class="text-center">
                <a href="{{ route('sesiones.scanner') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg inline-flex items-center">
                    <span class="mr-2">📱</span>
                    Ir al Scanner QR
                </a>
            </div>
        </div>
    </div>
</x-app-layout>