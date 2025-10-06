<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestión de Soporte - Tickets
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="text-sm text-gray-600">Total Tickets</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-yellow-50 rounded-lg shadow p-4">
                    <div class="text-sm text-yellow-800">Pendientes</div>
                    <div class="text-2xl font-bold text-yellow-900">{{ $stats['pendientes'] }}</div>
                </div>
                <div class="bg-blue-50 rounded-lg shadow p-4">
                    <div class="text-sm text-blue-800">En Revisión</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $stats['en_revision'] }}</div>
                </div>
                <div class="bg-green-50 rounded-lg shadow p-4">
                    <div class="text-sm text-gray-800">Resueltos</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['resueltos'] }}</div>
                </div>
                <div class="bg-red-50 rounded-lg shadow p-4">
                    <div class="text-sm text-red-800">Fallas</div>
                    <div class="text-2xl font-bold text-red-900">{{ $stats['fallas'] }}</div>
                </div>
                <div class="bg-indigo-50 rounded-lg shadow p-4">
                    <div class="text-sm text-gray-800">Sugerencias</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['sugerencias'] }}</div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-lg shadow mb-6 p-4">
                <form method="GET" action="{{ route('soporte.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select name="tipo" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Todos</option>
                            <option value="falla" {{ request('tipo') === 'falla' ? 'selected' : '' }}>Fallas</option>
                            <option value="sugerencia" {{ request('tipo') === 'sugerencia' ? 'selected' : '' }}>Sugerencias</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="estado" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_revision" {{ request('estado') === 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                            <option value="resuelto" {{ request('estado') === 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                            <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                        <select name="prioridad" class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Todas</option>
                            <option value="alta" {{ request('prioridad') === 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="media" {{ request('prioridad') === 'media' ? 'selected' : '' }}>Media</option>
                            <option value="baja" {{ request('prioridad') === 'baja' ? 'selected' : '' }}>Baja</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Filtrar
                        </button>
                        <a href="{{ route('soporte.index') }}" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Lista de Tickets -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($tickets->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            No hay tickets con los filtros seleccionados
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($tickets as $ticket)
                                <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    #{{ $ticket->id }} - {{ $ticket->titulo }}
                                                </h3>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $ticket->tipo === 'falla' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ $ticket->tipo === 'falla' ? 'Falla' : 'Sugerencia' }}
                                                </span>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($ticket->prioridad === 'alta') bg-red-100 text-red-800
                                                    @elseif($ticket->prioridad === 'media') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800
                                                    @endif">
                                                    {{ $ticket->prioridad_label }}
                                                </span>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($ticket->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                                    @elseif($ticket->estado === 'en_revision') bg-blue-100 text-blue-800
                                                    @elseif($ticket->estado === 'resuelto') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ $ticket->estado_label }}
                                                </span>
                                            </div>
                                            <p class="text-gray-600 text-sm mb-2">{{ Str::limit($ticket->descripcion, 150) }}</p>
                                            <div class="flex items-center text-xs text-gray-500 space-x-4">
                                                <span>Creado por: <strong>{{ $ticket->usuario->name }}</strong></span>
                                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex space-x-2">
                                            <button onclick="openModal({{ $ticket->id }}, '{{ $ticket->estado }}', '{{ addslashes($ticket->respuesta_admin ?? '') }}')" 
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Gestionar
                                            </button>
                                            <form method="POST" action="{{ route('soporte.destroy', $ticket) }}" onsubmit="return confirm('¿Eliminar este ticket?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para gestionar ticket -->
    <div id="modalGestionar" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Gestionar Ticket</h3>
                <form id="formGestionar" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                        <select name="estado" id="modalEstado" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="en_revision">En Revisión</option>
                            <option value="resuelto">Resuelto</option>
                            <option value="rechazado">Rechazado</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Respuesta al Usuario</label>
                        <textarea name="respuesta_admin" id="modalRespuesta" rows="4" 
                                  class="shadow border rounded w-full py-2 px-3 text-gray-700"
                                  placeholder="Escribe una respuesta para el usuario..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(ticketId, estado, respuesta) {
            document.getElementById('formGestionar').action = `/admin/soporte/${ticketId}/estado`;
            document.getElementById('modalEstado').value = estado;
            document.getElementById('modalRespuesta').value = respuesta;
            document.getElementById('modalGestionar').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modalGestionar').classList.add('hidden');
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('modalGestionar').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</x-app-layout>