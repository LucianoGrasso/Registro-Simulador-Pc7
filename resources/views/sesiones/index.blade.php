<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 {{ __('Historial de Sesiones') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alertas -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative" role="alert">
                    <span class="inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </span>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative" role="alert">
                    <span class="inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </span>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Filtros -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-700 mb-4">🔍 Filtros de Búsqueda</h5>
                    <form method="GET" action="{{ route('sesiones.index') }}" class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                   value="{{ request('fecha_inicio') }}" style="color: #000 !important; background: #fff !important;">
                        </div>
                        <div class="flex-1 min-w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                   value="{{ request('fecha_fin') }}" style="color: #000 !important; background: #fff !important;">
                        </div>
                        <div class="flex-1 min-w-64">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Alumno</label>
                            <input type="text" name="alumno_buscar" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" 
                                   placeholder="Nombre o NPI" value="{{ request('alumno_buscar') }}" style="color: #000 !important; background: #fff !important;">
                        </div>
                        <div class="min-w-40">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                            <select name="estado" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" style="color: #000 !important; background: #fff !important;">
                                <option value="">Todos</option>
                                <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Activa</option>
                                <option value="finalizada" {{ request('estado') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            🔍 Buscar
                        </button>
                        @if(request()->hasAny(['fecha_inicio', 'fecha_fin', 'alumno_buscar', 'estado']))
                            <a href="{{ route('sesiones.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                ↻ Limpiar
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Tabla de sesiones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    @if($sesiones->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Alumno
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha/Hora
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Duración
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actividad
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sesiones as $sesion)
                                    <tr id="sesion-{{ $sesion->id }}" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">#{{ $sesion->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-2xl mr-3">
                                                    @if($sesion->estado === 'activa')
                                                        🟡
                                                    @elseif($sesion->estado === 'finalizada')
                                                        🟢
                                                    @else
                                                        🔴
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $sesion->alumno->nombre_completo }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <code class="bg-gray-100 px-2 py-0.5 rounded text-xs">{{ $sesion->npi }}</code>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">📅 {{ $sesion->fecha->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">
                                                🕐 {{ $sesion->hora_inicio->format('H:i') }}
                                                @if($sesion->hora_fin)
                                                    → {{ $sesion->hora_fin->format('H:i') }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($sesion->duracion_minutos)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $sesion->duracion_formateada }}
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-500">{{ $sesion->tiempo_transcurrido }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($sesion->estado === 'activa')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    🔄 Activa
                                                </span>
                                            @elseif($sesion->estado === 'finalizada')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    ✅ Finalizada
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    ❌ Cancelada
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                                {{ Str::limit($sesion->actividad, 40) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Paginación -->
                        @if($sesiones->hasPages())
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                            {{ $sesiones->links() }}
                        </div>
                        @endif
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <div class="text-4xl mb-4">📋</div>
                                <div class="text-lg font-medium">No se encontraron sesiones</div>
                                <div class="text-sm mt-2">
                                    Intenta ajustar los filtros de búsqueda
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div id="modalEliminar" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirmar Eliminación</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        ¿Estás seguro de que deseas eliminar esta sesión?
                    </p>
                    <p class="text-sm font-semibold text-gray-700 mt-2" id="infoSesion"></p>
                    <p class="text-xs text-red-600 mt-2">Esta acción no se puede deshacer.</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="btnCancelar" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancelar
                    </button>
                    <button id="btnConfirmarEliminar" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let sesionIdEliminar = null;
        const modal = document.getElementById('modalEliminar');

        // Abrir modal de eliminación
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', function() {
                sesionIdEliminar = this.dataset.id;
                const alumno = this.dataset.alumno;
                const fecha = this.dataset.fecha;
                
                document.getElementById('infoSesion').textContent = `Sesión de ${alumno} - ${fecha}`;
                modal.classList.remove('hidden');
            });
        });

        // Cerrar modal
        document.getElementById('btnCancelar').addEventListener('click', function() {
            modal.classList.add('hidden');
            sesionIdEliminar = null;
        });

        // Confirmar eliminación
        document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
            if (!sesionIdEliminar) return;
            
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = 'Eliminando...';
            
            fetch('/sesiones/' + sesionIdEliminar, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modal.classList.add('hidden');
                    
                    const fila = document.getElementById('sesion-' + sesionIdEliminar);
                    if (fila) {
                        fila.style.transition = 'opacity 0.3s';
                        fila.style.opacity = '0';
                        
                        setTimeout(() => {
                            fila.remove();
                            
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative';
                            alertDiv.innerHTML = `
                                <span class="inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    ${data.message}
                                </span>
                            `;
                            
                            const container = document.querySelector('.max-w-7xl');
                            if (container) {
                                container.insertBefore(alertDiv, container.firstChild);
                            }
                            
                            const tbody = document.querySelector('tbody');
                            if (tbody && tbody.children.length === 0) {
                                setTimeout(() => location.reload(), 1500);
                            }
                        }, 300);
                    }
                } else {
                    alert('Error: ' + data.message);
                    modal.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar la sesión. Por favor, revisa la consola.');
                modal.classList.add('hidden');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Eliminar';
                sesionIdEliminar = null;
            });
        });

        // Cerrar modal al hacer clic fuera
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                sesionIdEliminar = null;
            }
        });
    });
    </script>
    @endpush
</x-app-layout>