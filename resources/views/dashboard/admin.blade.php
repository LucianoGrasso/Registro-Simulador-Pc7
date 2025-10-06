<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Simulador ') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('sesiones.scanner') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    📱 Scanner
                </a>
                <a href="{{ route('alumnos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    🎓 Alumnos
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Estadísticas principales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-3xl">🎓</div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Alumnos Activos
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $estadisticas['total_alumnos'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-3xl">📊</div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Sesiones Hoy
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900" id="sesiones-hoy">
                                        {{ $estadisticas['total_sesiones_hoy'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-3xl">⏱️</div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Sesiones Activas
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900" id="sesiones-activas">
                                        {{ $estadisticas['sesiones_activas'] }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="text-3xl">🕐</div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Horas Hoy
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900" id="tiempo-total">
                                        {{ round($estadisticas['tiempo_total_hoy'] / 60, 1) }}h
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas de sesiones que necesitan atención -->
            @if($sesionesAtencion->count() > 0)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <div class="flex items-center">
                    <div class="text-xl mr-2">⚠️</div>
                    <div>
                        <strong>{{ $sesionesAtencion->count() }} sesión(es) necesitan atención</strong>
                        <div class="text-sm mt-1">
                            @foreach($sesionesAtencion as $sesion)
                                <div>{{ $sesion->alumno->nombre_completo }} - {{ $sesion->tiempo_transcurrido }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Sesiones Activas Actuales -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            🔄 Sesiones Activas ({{ $sesionesActivas->count() }})
                        </h3>
                        
                        <div class="space-y-3 max-h-80 overflow-y-auto" id="sesiones-activas-lista">
                            @forelse($sesionesActivas as $sesion)
                                <div class="border-l-4 border-yellow-400 bg-yellow-50 p-3 rounded">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-900">
                                                {{ $sesion->alumno->nombre_completo }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                NPI: {{ $sesion->alumno->npi }} | 
                                                Inicio: {{ $sesion->hora_inicio->format('H:i') }} |
                                                Usuario: {{ $sesion->usuarioInicio->name }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ Str::limit($sesion->actividad, 80) }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm font-medium text-yellow-700">
                                                {{ $sesion->tiempo_transcurrido }}
                                            </span>
                                            @if($sesion->necesitaAtencion())
                                                <div class="text-xs text-red-600 font-bold">
                                                    ⚠️ Atención
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-20">
                                    ✈️ No hay sesiones activas
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sesiones Recientes -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            📋 Sesiones Recientes
                        </h3>
                        
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @forelse($sesionesRecientes as $sesion)
                                <div class="border border-gray-200 rounded p-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-medium text-gray-900">
                                                {{ $sesion->alumno->nombre_completo }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                {{ $sesion->fecha->format('d/m/Y') }} | 
                                                {{ $sesion->hora_inicio->format('H:i') }}
                                                @if($sesion->hora_fin)
                                                    - {{ $sesion->hora_fin->format('H:i') }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ Str::limit($sesion->actividad, 60) }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            @if($sesion->estado === 'activa')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Activa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $sesion->duracion_formateada }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">
                                    No hay sesiones recientes
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de la semana -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        📈 Actividad de los últimos 7 días
                    </h3>
                    
                    <div class="grid grid-cols-7 gap-2">
                        @foreach($estadisticasSemana as $dia)
                            <div class="text-center">
                                <div class="text-xs text-gray-500 mb-1">
                                    {{ ucfirst(\Carbon\Carbon::createFromFormat('d/m', $dia['fecha'])->locale('es')->isoFormat('ddd')) }}
                                </div>
                                <div class="text-xs text-gray-700 mb-2">{{ $dia['fecha'] }}</div>
                                <div class="bg-red-100 rounded-lg p-2 min-h-16">
                                    <div class="text-sm font-medium text-gray-900">{{ $dia['sesiones'] }}</div>
                                    <div class="text-xs text-gray-600">sesiones</div>
                                    @if($dia['minutos'] > 0)
                                        <div class="text-xs text-gray-600 mt-1">
                                            {{ round($dia['minutos'] / 60, 1) }}h
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Alumnos más activos del mes -->
            @if($alumnosActivos->count() > 0)
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        🏆 Alumnos más activos este mes
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($alumnosActivos as $index => $alumno)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="text-lg mr-3">
                                        @if($index === 0) 🥇
                                        @elseif($index === 1) 🥈
                                        @elseif($index === 2) 🥉
                                        @else {{ $index + 1 }}.
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $alumno->nombre_completo }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-blue-600">
                                        {{ $alumno->sesiones_count }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        sesiones
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Actualizar datos cada 30 segundos
            function actualizarDashboard() {
                fetch('{{ route("dashboard.datos") }}')
                    .then(response => response.json())
                    .then(data => {
                        // Actualizar estadísticas
                        document.getElementById('sesiones-hoy').textContent = data.estadisticas.sesiones_hoy;
                        document.getElementById('sesiones-activas').textContent = data.estadisticas.sesiones_activas;
                        document.getElementById('tiempo-total').textContent = Math.round(data.estadisticas.tiempo_total_hoy / 60 * 10) / 10 + 'h';
                        
                        // Actualizar lista de sesiones activas
                        const lista = document.getElementById('sesiones-activas-lista');
                        let html = '';
                        
                        if (data.sesiones_activas.length === 0) {
                            html = '<p class="text-gray-500 text-center py-4">✈️ No hay sesiones activas</p>';
                        } else {
                            data.sesiones_activas.forEach(sesion => {
                                html += `
                                    <div class="border-l-4 border-yellow-400 bg-yellow-50 p-3 rounded">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-900">${sesion.alumno}</p>
                                                <p class="text-sm text-gray-600">
                                                    NPI: ${sesion.npi} | Inicio: ${sesion.hora_inicio}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-medium text-yellow-700">${sesion.tiempo_transcurrido}</span>
                                                ${sesion.necesita_atencion ? '<div class="text-xs text-red-600 font-bold">⚠️ Atención</div>' : ''}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                        
                        lista.innerHTML = html;
                        
                        console.log('Dashboard actualizado:', data.timestamp);
                    })
                    .catch(error => console.error('Error actualizando dashboard:', error));
            }
            
            // Actualizar cada 30 segundos
            setInterval(actualizarDashboard, 30000);
        });
    </script>
</x-app-layout>