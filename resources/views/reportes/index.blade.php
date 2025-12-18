<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Sistema de Reportes y Estadísticas
        </h2>
    </x-slot>

    <style>
        .report-card {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb; /* Gris suave por defecto */
        }
        .report-card:hover {
            transform: translateY(-5px); /* Se levanta un poquito */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Sombra */
            border-color: #3b82f6; /* Borde Azul al pasar mouse */
            background-color: #feffff; /* Un blanco brillante */
            cursor: pointer;
        }
        /* Colores específicos por tarjeta al hacer hover */
        .card-mensual:hover .icon-bg { background-color: #eff6ff; color: #2563eb; }
        .card-anual:hover .icon-bg { background-color: #f3e8ff; color: #9333ea; }
        .card-pdf:hover .icon-bg { background-color: #fef2f2; color: #dc2626; }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Navegación Principal -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Seleccione el tipo de reporte</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <a href="{{ route('reportes.mensual') }}" class="report-card card-mensual group block bg-white border border-gray-200 rounded-xl hover:border-blue-400 hover:shadow-md transition-all p-6 text-center">
                            <div class="w-12 h-12 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div class="text-lg font-bold text-gray-800 mb-1">Reporte Mensual</div>
                            <div class="text-sm text-gray-500">Análisis detallado por mes</div>
                        </a>
                        
                        <a href="{{ route('reportes.anual') }}" class="report-card group block bg-white border border-gray-200 rounded-xl hover:border-purple-400 hover:shadow-md transition-all p-6 text-center">
                            <div class="w-12 h-12 mx-auto bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="text-lg font-bold text-gray-800 mb-1">Reporte Anual</div>
                            <div class="text-sm text-gray-500">Resumen del año completo</div>
                        </a>
                        
                        <div class="report-card group block bg-white border border-gray-200 rounded-xl hover:border-red-400 hover:shadow-md transition-all p-6 text-center cursor-pointer" onclick="mostrarExportacion()">
                            <div class="w-12 h-12 mx-auto bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="text-lg font-bold text-gray-800 mb-1">Exportar PDF</div>
                            <div class="text-sm text-gray-500">Descargar informes</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen Global Histórico (Rediseñado) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">📈 Impacto Histórico del Simulador</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        <!-- 1. Total Histórico de Sesiones -->
                        <div class="flex items-start p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="p-3 bg-blue-500 rounded-lg text-white shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Sesiones Totales</p>
                                <p class="text-2xl font-bold text-gray-800" id="total-sesiones">-</p>
                                <p class="text-xs text-gray-400 mt-1">Desde el inicio</p>
                            </div>
                        </div>

                        <!-- 2. Tiempo Promedio Global -->
                        <div class="flex items-start p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="p-3 bg-green-500 rounded-lg text-white shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Tiempo Promedio</p>
                                <p class="text-2xl font-bold text-gray-800" id="tiempo-promedio">- min</p>
                                <p class="text-xs text-gray-400 mt-1">Por sesión</p>
                            </div>
                        </div>

                        <!-- 3. Horas Totales de Vuelo -->
                        <div class="flex items-start p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="p-3 bg-purple-500 rounded-lg text-white shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Horas Totales</p>
                                <p class="text-2xl font-bold text-gray-800" id="horas-totales">- h</p>
                                <p class="text-xs text-gray-400 mt-1">Acumuladas</p>
                            </div>
                        </div>

                        <!-- 4. Ahorro Total -->
                        <div class="flex items-start p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="p-3 bg-green-500 rounded-lg text-white shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Ahorro Histórico</p>
                                <p class="text-2xl font-bold text-gray-700" id="ahorro-total">$-</p>
                                <p class="text-xs text-gray-400 mt-1">USD Estimados</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal de Exportación (Manteniendo funcionalidad) -->
            <div id="modal-exportacion" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                    <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Exportar Reportes PDF</h3>
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
                    // Datos Globales Históricos
                    document.getElementById('total-sesiones').textContent = data.total_historico_sesiones || '0';
                    document.getElementById('tiempo-promedio').textContent = (data.tiempo_promedio_global || 0) + ' min';
                    document.getElementById('horas-totales').textContent = (data.horas_totales_global || 0) + ' h';
                    
                    // Cálculo de Dinero (Horas Totales * 650)
                    // Nota: Asegúrate de que el backend envíe 'horas_totales_global' o calcúlalo aquí si envían minutos
                    const horas = parseFloat(data.horas_totales_global || 0);
                    const ahorro = horas * 650;
                    document.getElementById('ahorro-total').textContent = '$ ' + ahorro.toLocaleString('es-CL');
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