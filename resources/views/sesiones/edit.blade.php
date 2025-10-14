<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ {{ __('Editar Sesión') }} #{{ $sesion->id }}
        </h2>
    </x-slot>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ {{ __('Editar Sesión') }} #{{ $sesion->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Botón Volver -->
            <div class="mb-4">
                <a href="{{ route('sesiones.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>

            <!-- Alertas -->
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative">
                    <span class="inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                    <p class="font-semibold mb-2">Errores de validación:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Card de información del alumno -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                <div class="p-6">
                    <h5 class="text-lg font-semibold text-gray-700 mb-4">👤 Información del Alumno</h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nombre Completo</p>
                            <p class="text-base font-medium text-gray-900">{{ $sesion->alumno->nombre_completo }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NPI</p>
                            <p class="text-base font-mono bg-gray-100 px-2 py-1 rounded inline-block">{{ $sesion->alumno->npi }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Especialidad</p>
                            <p class="text-base font-medium text-gray-900">{{ $sesion->alumno->especialidad ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de edición -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-700 mb-4">📝 Datos de la Sesión</h5>
                    
                    <form method="POST" action="{{ route('sesiones.update', $sesion->id) }}" id="formEditar">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Fecha -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="fecha" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('fecha') border-red-500 @enderror" 
                                       value="{{ old('fecha', $sesion->fecha->format('Y-m-d')) }}"
                                       required
                                       style="color: #000 !important; background: #fff !important;">
                                @error('fecha')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select name="estado" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('estado') border-red-500 @enderror" 
                                        id="estadoSelect"
                                        required
                                        style="color: #000 !important; background: #fff !important;">
                                    <option value="activa" {{ old('estado', $sesion->estado) == 'activa' ? 'selected' : '' }}>
                                        🟡 Activa
                                    </option>
                                    <option value="finalizada" {{ old('estado', $sesion->estado) == 'finalizada' ? 'selected' : '' }}>
                                        🟢 Finalizada
                                    </option>
                                    <option value="cancelada" {{ old('estado', $sesion->estado) == 'cancelada' ? 'selected' : '' }}>
                                        🔴 Cancelada
                                    </option>
                                </select>
                                @error('estado')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hora Inicio -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Hora Inicio <span class="text-red-500">*</span>
                                </label>
                                <input type="time" 
                                       name="hora_inicio" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('hora_inicio') border-red-500 @enderror" 
                                       value="{{ old('hora_inicio', $sesion->hora_inicio->format('H:i')) }}"
                                       required
                                       style="color: #000 !important; background: #fff !important;">
                                @error('hora_inicio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hora Fin -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Hora Fin
                                </label>
                                <input type="time" 
                                       name="hora_fin" 
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('hora_fin') border-red-500 @enderror" 
                                       value="{{ old('hora_fin', $sesion->hora_fin?->format('H:i')) }}"
                                       id="horaFin"
                                       style="color: #000 !important; background: #fff !important;">
                                <p class="mt-1 text-xs text-gray-500">Dejar vacío si aún está activa</p>
                                @error('hora_fin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Duración calculada -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Duración Calculada
                                </label>
                                <input type="text" 
                                       class="block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-600" 
                                       id="duracionCalculada"
                                       value="{{ $sesion->duracion_formateada }}"
                                       readonly>
                                <p class="mt-1 text-xs text-gray-500">Se calcula automáticamente</p>
                            </div>

                            <!-- Actividad -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Actividad Realizada <span class="text-red-500">*</span>
                                </label>
                                <textarea name="actividad" 
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('actividad') border-red-500 @enderror" 
                                          rows="3"
                                          maxlength="500"
                                          required
                                          style="color: #000 !important; background: #fff !important;">{{ old('actividad', $sesion->actividad) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Describe brevemente la actividad realizada (máx. 500 caracteres)</p>
                                @error('actividad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Observaciones -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Observaciones
                                </label>
                                <textarea name="observaciones" 
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('observaciones') border-red-500 @enderror" 
                                          rows="2"
                                          maxlength="1000"
                                          style="color: #000 !important; background: #fff !important;">{{ old('observaciones', $sesion->observaciones) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Notas adicionales o comentarios especiales (máx. 1000 caracteres)</p>
                                @error('observaciones')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <span class="font-medium">Iniciada por:</span> 
                                    {{ $sesion->usuarioInicio->name ?? 'N/A' }}
                                </div>
                                <div>
                                    <span class="font-medium">Finalizada por:</span> 
                                    {{ $sesion->usuarioFin->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-between mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('sesiones.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Advertencia -->
            <div class="mt-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-medium">Nota importante</p>
                        <p class="text-sm mt-1">Los cambios en las sesiones se registran para auditoría. Asegúrate de que los datos sean correctos antes de guardar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@push('scripts')
<script>
// Calcular duración automáticamente
function calcularDuracion() {
    const horaInicio = document.querySelector('input[name="hora_inicio"]').value;
    const horaFin = document.querySelector('input[name="hora_fin"]').value;
    const duracionInput = document.getElementById('duracionCalculada');
    
    if (horaInicio && horaFin) {
        const [hIni, mIni] = horaInicio.split(':').map(Number);
        const [hFin, mFin] = horaFin.split(':').map(Number);
        
        const minutosTotales = (hFin * 60 + mFin) - (hIni * 60 + mIni);
        
        if (minutosTotales <= 0) {
            duracionInput.value = 'Hora fin debe ser mayor';
            duracionInput.classList.add('text-danger');
            return;
        }
        
        duracionInput.classList.remove('text-danger');
        const horas = Math.floor(minutosTotales / 60);
        const minutos = minutosTotales % 60;
        
        if (horas > 0) {
            duracionInput.value = `${horas}h ${minutos > 0 ? minutos + 'm' : ''}`;
        } else {
            duracionInput.value = `${minutos}m`;
        }
    } else {
        duracionInput.value = '-';
        duracionInput.classList.remove('text-danger');
    }
}

// Validar estado vs hora_fin
function validarEstadoHoraFin() {
    const estado = document.getElementById('estadoSelect').value;
    const horaFinInput = document.querySelector('input[name="hora_fin"]');
    
    if (estado === 'finalizada' || estado === 'cancelada') {
        horaFinInput.required = true;
        if (!horaFinInput.value) {
            horaFinInput.classList.add('border-warning');
        }
    } else {
        horaFinInput.required = false;
        horaFinInput.classList.remove('border-warning');
    }
}

// Event listeners
document.querySelector('input[name="hora_inicio"]').addEventListener('change', calcularDuracion);
document.querySelector('input[name="hora_fin"]').addEventListener('change', calcularDuracion);
document.getElementById('estadoSelect').addEventListener('change', validarEstadoHoraFin);

// Validación antes de enviar
document.getElementById('formEditar').addEventListener('submit', function(e) {
    const estado = document.getElementById('estadoSelect').value;
    const horaFin = document.querySelector('input[name="hora_fin"]').value;
    
    if ((estado === 'finalizada' || estado === 'cancelada') && !horaFin) {
        e.preventDefault();
        alert('Las sesiones finalizadas o canceladas deben tener hora de fin.');
        document.querySelector('input[name="hora_fin"]').focus();
        return false;
    }
});

// Ejecutar al cargar
calcularDuracion();
validarEstadoHoraFin();
</script>
@endpush
</x-app-layout>