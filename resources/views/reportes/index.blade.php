<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sistema de Reportes
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Navegación de reportes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('reportes.mensual') }}" class="bg-green-500 hover:bg-green-600 text-white p-6 rounded-lg text-center transition-colors">
                            <div class="text-3xl mb-3">📈</div>
                            <div class="text-lg font-medium mb-2">Reporte Mensual</div>
                            <div class="text-sm opacity-80">Análisis detallado por mes</div>
                        </a>
                        
                        <a href="{{ route('reportes.anual') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-6 rounded-lg text-center transition-colors">
                            <div class="text-3xl mb-3">📊</div>
                            <div class="text-lg font-medium mb-2">Reporte Anual</div>
                            <div class="text-sm opacity-80">Resumen del año completo</div>
                        </a>
                        
                        <div class="bg-red-500 hover:bg-red-600 text-white p-6 rounded-lg text-center transition-colors cursor-pointer" onclick="mostrarExportacion()">
                            <div class="text-3xl mb-3">📄</div>
                            <div class="text-lg font-medium mb-2">Exportar Reportes</div>
                            <div class="text-sm opacity-80">Descargar en PDF</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen rápido -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500">Sesiones Hoy</div>
                            <div class="text-2xl font-bold text-gray-900" id="sesiones-hoy">-</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500">Tiempo Promedio</div>
                            <div class="text-2xl font-bold text-gray-900" id="tiempo-promedio">- min</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500">Este Mes</div>
                            <div class="text-2xl font-bold text-gray-900" id="sesiones-mes">-</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-500">Este Año</div>
                            <div class="text-2xl font-bold text-gray-900" id="sesiones-año">-</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Exportación -->
            <div id="modal-exportacion" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Exportar Reportes PDF
                                    </h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tipo de Reporte</label>
                                            <select id="tipo-reporte" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900">
                                                <option value="mensual">Reporte Mensual</option>
                                                <option value="anual">Reporte Anual</option>
                                            </select>
                                        </div>
                                        <div id="selector-mes">
                                            <label class="block text-sm font-medium text-gray-700">Mes y Año</label>
                                            <input type="month" id="mes-año" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900" value="{{ now()->format('Y-m') }}">
                                        </div>
                                        <div id="selector-año" class="hidden">
                                            <label class="block text-sm font-medium text-gray-700">Año</label>
                                            <select id="año-select" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-gray-900">
                                                @for($i = now()->year; $i >= now()->year - 5; $i--)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" onclick="exportarReporte()" class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Descargar PDF
                            </button>
                            <button type="button" onclick="cerrarModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            cargarResumenRapido();
            
            // Cambiar selector según tipo de reporte
            document.getElementById('tipo-reporte').addEventListener('change', function() {
                const tipo = this.value;
                const selectorMes = document.getElementById('selector-mes');
                const selectorAño = document.getElementById('selector-año');
                
                if (tipo === 'mensual') {
                    selectorMes.classList.remove('hidden');
                    selectorAño.classList.add('hidden');
                } else {
                    selectorMes.classList.add('hidden');
                    selectorAño.classList.remove('hidden');
                }
            });
        });

        function cargarResumenRapido() {
            fetch('/reportes/resumen-rapido')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('sesiones-hoy').textContent = data.sesiones_hoy || '0';
                    document.getElementById('tiempo-promedio').textContent = (data.tiempo_promedio || 0) + ' min';
                    document.getElementById('sesiones-mes').textContent = data.sesiones_mes || '0';
                    document.getElementById('sesiones-año').textContent = data.sesiones_año || '0';
                })
                .catch(error => console.error('Error:', error));
        }

        function mostrarExportacion() {
            document.getElementById('modal-exportacion').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modal-exportacion').classList.add('hidden');
        }

        function exportarReporte() {
            const tipo = document.getElementById('tipo-reporte').value;
            
            let url = `/reportes/exportar?tipo=${tipo}&formato=pdf`;
            
            if (tipo === 'mensual') {
                const mesAño = document.getElementById('mes-año').value;
                url += `&periodo=${mesAño}`;
            } else {
                const año = document.getElementById('año-select').value;
                url += `&periodo=${año}`;
            }
            
            window.open(url, '_blank');
            cerrarModal();
        }
    </script>
</x-app-layout>