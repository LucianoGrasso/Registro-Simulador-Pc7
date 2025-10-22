<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles del Alumno
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('alumnos.edit', $alumno->id) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    ✏️ Editar
                </a>
                <a href="{{ route('alumnos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Información del Alumno -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Datos Principales -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                📋 Información Personal
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                    <div class="mt-1 text-lg text-gray-900">
                                        {{ $alumno->nombre_completo }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">RUT/DNI</label>
                                    <div class="mt-1 text-lg font-mono text-gray-900">
                                        {{ $alumno->rut_formateado }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NPI</label>
                                    <div class="mt-1 text-lg font-mono text-gray-900">
                                        {{ $alumno->npi }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                    <div class="mt-1 text-lg  text-gray-900">
                                        {{ $alumno->correo ?: 'No registrado' }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                                    <div class="mt-1">
                                        @if($alumno->sesiones_activas_count > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                🔄 En simulador ({{ $alumno->sesiones_activas_count }} sesión(es))
                                            </span>
                                        @elseif($alumno->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                ✅ Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                ❌ Inactivo
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Fecha de Registro</label>
                                    <div class="mt-1 text-sm text-gray-600">
                                        {{ $alumno->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estadísticas del Alumno -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                📊 Estadísticas del Alumno
                            </h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ $estadisticas['total_sesiones'] }}
                                    </div>
                                    <div class="text-sm text-gray-500">Total Sesiones</div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-600">
                                        {{ $estadisticas['sesiones_activas'] }}
                                    </div>
                                    <div class="text-sm text-gray-500">Activas Ahora</div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ round($estadisticas['tiempo_total_minutos'] / 60, 1) }}h
                                    </div>
                                    <div class="text-sm text-gray-500">Tiempo Total</div>
                                </div>
                                
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">
                                        {{ $estadisticas['promedio_duracion'] ? round($estadisticas['promedio_duracion'], 0) : 0 }}m
                                    </div>
                                    <div class="text-sm text-gray-500">Promedio/Sesión</div>
                                </div>
                            </div>
                            
                            @if($estadisticas['ultima_sesion'])
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <div class="text-sm font-medium text-gray-700">Última Sesión:</div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        {{ $estadisticas['ultima_sesion']->fecha->format('d/m/Y') }} - 
                                        {{ $estadisticas['ultima_sesion']->hora_inicio->format('H:i') }}
                                        @if($estadisticas['ultima_sesion']->hora_fin)
                                            a {{ $estadisticas['ultima_sesion']->hora_fin->format('H:i') }}
                                            ({{ $estadisticas['ultima_sesion']->duracion_formateada }})
                                        @else
                                            - <span class="text-yellow-600 font-medium">En curso</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ Str::limit($estadisticas['ultima_sesion']->actividad, 100) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Panel QR Code -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                📱 Código QR
                            </h3>
                            
                            <div class="text-center">
                                @if($alumno->qr_image_path)
                                    <!-- Mostrar QR SVG -->
                                    <div class="mb-4 p-4 bg-gray-50 rounded-lg inline-block">
                                        {!! $alumno->qr_svg !!}
                                    </div>
                                    
                                    <div class="text-sm text-gray-600 mb-4">
                                        <div class="font-medium">Contenido del QR:</div>
                                        <div class="font-mono text-lg bg-gray-100 py-2 px-3 rounded mt-1">
                                            {{ str_replace('-', '', $alumno->npi) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Acciones del QR -->
                                    <div class="space-y-2">
                                        <a href="{{ route('alumnos.descargar-qr', $alumno->id) }}" 
                                           class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center justify-center">
                                            <span class="mr-2">💾</span>
                                            Descargar QR
                                        </a>
                                        
                                        <button onclick="imprimirQR()" 
                                                class="w-full bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded inline-flex items-center justify-center">
                                            <span class="mr-2">🖨️</span>
                                            Imprimir QR
                                        </button>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <div class="text-4xl text-gray-400 mb-4">📱</div>
                                        <div class="text-gray-500 mb-4">QR no generado</div>
                                        <form method="POST" action="{{ route('alumnos.regenerar-qr', $alumno->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                Generar QR
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Historial de Sesiones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        🕒 Historial de Sesiones ({{ $alumno->sesiones->count() }})
                    </h3>
                    
                    @if($alumno->sesiones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Horario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Duración
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actividad
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($alumno->sesiones->take(20) as $sesion)
                                        <tr class="{{ $sesion->estado === 'activa' ? 'bg-yellow-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $sesion->fecha->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $sesion->hora_inicio->format('H:i') }}
                                                @if($sesion->hora_fin)
                                                    - {{ $sesion->hora_fin->format('H:i') }}
                                                @else
                                                    <span class="text-yellow-600">- En curso</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($sesion->duracion_minutos)
                                                    {{ $sesion->duracion_formateada }}
                                                @else
                                                    <span class="text-yellow-600">{{ $sesion->tiempo_transcurrido }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                                {{ Str::limit($sesion->actividad, 50) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($sesion->estado === 'activa')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        🔄 Activa
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        ✅ Completada
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($alumno->sesiones->count() > 20)
                            <div class="mt-4 text-center">
                                <span class="text-sm text-gray-500">
                                    Mostrando las 20 sesiones más recientes de {{ $alumno->sesiones->count() }} total
                                </span>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <div class="text-4xl text-gray-400 mb-4">✈️</div>
                            <div class="text-gray-500">Aún no hay sesiones registradas</div>
                            <div class="text-sm text-gray-400 mt-2">
                                Las sesiones aparecerán aquí cuando el alumno use el simulador
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Script para imprimir QR -->
    <script>
        function imprimirQR() {
            const qrContent = `
                <div style="text-align: center; padding: 20px; font-family: Arial, sans-serif;">
                    <h2>{{ $alumno->nombre_completo }}</h2>
                    <p><strong>NPI:</strong> {{ $alumno->npi_formateado }}</p>
                    <div style="margin: 20px 0;">
                        {!! $alumno->qr_svg ?? '' !!}
                    </div>
                    <p style="font-size: 12px; color: #666;">
                        Código QR para registro en simulador de vuelo<br>
                        Generado el {{ now()->format('d/m/Y H:i') }}
                    </p>
                </div>
            `;
            
            const ventanaImprimir = window.open('', '_blank');
            ventanaImprimir.document.write(`
                <html>
                <head>
                    <title>QR - {{ $alumno->nombre_completo }}</title>
                    <style>
                        body { margin: 0; padding: 20px; }
                        @media print {
                            body { margin: 0; }
                        }
                    </style>
                </head>
                <body>
                    ${qrContent}
                </body>
                </html>
            `);
            ventanaImprimir.document.close();
            ventanaImprimir.focus();
            ventanaImprimir.print();
        }
    </script>
</x-app-layout>