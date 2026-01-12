<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Información del Operador
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition-colors shadow">
                ← Volver al Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg transition-colors">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-200 mb-4 flex items-center">
                        <span class="text-2xl mr-3">🛡️</span>
                        Permisos de Operador
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-blue-800 dark:text-blue-300 mb-3">✅ Puedes hacer:</h4>
                            <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-200">
                                <li class="flex items-center">
                                    <span class="text-green-500 dark:text-green-400 mr-2">•</span>
                                    Registrar sesiones con scanner QR
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-500 dark:text-green-400 mr-2">•</span>
                                    Ver sesiones activas en tiempo real
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-500 dark:text-green-400 mr-2">•</span>
                                    Ver estadísticas del día
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-500 dark:text-green-400 mr-2">•</span>
                                    Ver historial de sesiones del día
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-500 dark:text-green-400 mr-2">•</span>
                                    Acceder al dashboard informativo
                                </li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-blue-800 dark:text-blue-300 mb-3">❌ No puedes hacer:</h4>
                            <ul class="space-y-2 text-sm text-blue-700 dark:text-blue-200">
                                <li class="flex items-center">
                                    <span class="text-red-500 dark:text-red-400 mr-2">•</span>
                                    Crear, editar o eliminar alumnos
                                </li>
                                <li class="flex items-center">
                                    <span class="text-red-500 dark:text-red-400 mr-2">•</span>
                                    Generar reportes completos
                                </li>
                                <li class="flex items-center">
                                    <span class="text-red-500 dark:text-red-400 mr-2">•</span>
                                    Cambiar configuración de cuenta
                                </li>
                                <li class="flex items-center">
                                    <span class="text-red-500 dark:text-red-400 mr-2">•</span>
                                    Acceder a funciones administrativas
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border dark:border-gray-700 transition-colors">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6 flex items-center">
                        <span class="text-2xl mr-3">📊</span>
                        Tus Estadísticas
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                {{ auth()->user()->sesionesIniciadas()->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sesiones Iniciadas</div>
                        </div>
                        
                        <div class="text-center p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                                {{ auth()->user()->sesionesFinalizadas()->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sesiones Finalizadas</div>
                        </div>
                        
                        <div class="text-center p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                                {{ auth()->user()->sesionesIniciadas()->whereDate('created_at', today())->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sesiones Hoy</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg transition-colors">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-200 mb-4 flex items-center">
                        <span class="text-2xl mr-3">📞</span>
                        ¿Necesitas ayuda?
                    </h3>
                    
                    <div class="text-sm text-yellow-800 dark:text-yellow-200">
                        <p class="mb-3">
                            Si necesitas realizar acciones administrativas, 
                            contacta al Encargado del Simulador - <span class="font-bold">Luciano Grasso</span>.
                        </p>
                        
                        <div class="bg-yellow-100 dark:bg-yellow-900/40 rounded p-4 border border-yellow-200 dark:border-yellow-800/50">
                            <p class="font-medium text-yellow-900 dark:text-yellow-100">Solicitudes comunes:</p>
                            <ul class="mt-2 space-y-1 ml-1">
                                <li>• Actualización de información personal</li>
                                <li>• Problemas con el scanner</li>
                                <li>• Dudas sobre el sistema</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('sesiones.scanner') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg inline-flex items-center shadow-lg transition-transform hover:scale-105">
                    <span class="mr-2">📱</span>
                    Ir al Scanner QR
                </a>
            </div>
        </div>
    </div>
</x-app-layout>