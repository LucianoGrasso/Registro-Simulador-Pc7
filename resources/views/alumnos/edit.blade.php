<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Alumno
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('alumnos.show', $alumno->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    👁️ Ver Detalles
                </a>
                <a href="{{ route('alumnos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    ← Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alertas de validación -->
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <div class="flex items-center mb-2">
                        <span class="text-xl mr-2">❌</span>
                        <strong>Por favor corrige los siguientes errores:</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Información actual del alumno -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="text-2xl mr-3">
                        @if($alumno->sesiones_activas_count > 0)
                            🟡
                        @elseif($alumno->is_active)
                            🟢
                        @else
                            🔴
                        @endif
                    </div>
                    <div>
                        <div class="font-medium text-blue-900">
                            Editando: {{ $alumno->nombre_completo }}
                        </div>
                        <div class="text-sm text-blue-700">
                            NPI actual: {{ $alumno->npi }} | 
                            Estado: {{ $alumno->is_active ? 'Activo' : 'Inactivo' }}
                            @if($alumno->sesiones_activas_count > 0)
                                | ⚠️ Tiene {{ $alumno->sesiones_activas_count }} sesión(es) activa(s)
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">
                        ✏️ Datos del Alumno
                    </h3>

                    <form method="POST" action="{{ route('alumnos.update', $alumno->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Nombre Completo -->
                            <div class="md:col-span-2">
                                <label for="nombre_completo" class="block text-sm font-medium text-gray-700">
                                    Nombre Completo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="nombre_completo" 
                                       name="nombre_completo" 
                                       value="{{ old('nombre_completo', $alumno->nombre_completo) }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nombre_completo') border-red-500 @enderror"
                                       placeholder="Ingresa el nombre completo del alumno"
                                       style="color: #000 !important; background: #fff !important;"
                                       required>
                                @error('nombre_completo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- RUT/DNI -->
                            <div>
                                <label for="rut_dni" class="block text-sm font-medium text-gray-700">
                                    RUT/DNI <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="rut_dni" 
                                       name="rut_dni" 
                                       value="{{ old('rut_dni', $alumno->rut_dni) }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('rut_dni') border-red-500 @enderror"
                                       placeholder="12.345.678-9"
                                       style="color: #000 !important; background: #fff !important;"
                                       required>
                                @error('rut_dni')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- NPI -->
                            <div>
                                <label for="npi" class="block text-sm font-medium text-gray-700">
                                    NPI <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="npi" 
                                       name="npi" 
                                       value="{{ old('npi', $alumno->npi) }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 font-mono @error('npi') border-red-500 @enderror"
                                       placeholder="1234567-8"
                                       pattern="[0-9]{7}-[0-9]{1}"
                                       style="color: #000 !important; background: #fff !important;"
                                       required>
                                <p class="mt-1 text-xs text-gray-500">
                                    Formato: 7 dígitos + guión + 1 dígito verificador (ej: 1234567-8)
                                </p>
                                @error('npi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @if($alumno->npi !== old('npi', $alumno->npi))
                                    <div class="mt-1 p-2 bg-yellow-100 border border-yellow-400 text-yellow-700 text-sm rounded">
                                        ⚠️ Si cambias el NPI, se regenerará automáticamente el código QR
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Correo -->
                            <div>
                                <label for="correo" class="block text-sm font-medium text-gray-700">
                                    Correo Electrónico
                                </label>
                                <input type="email" 
                                       id="correo" 
                                       name="correo" 
                                       value="{{ old('correo', $alumno->correo) }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('correo') border-red-500 @enderror"
                                       placeholder="alumno@email.com"
                                       style="color: #000 !important; background: #fff !important;">
                                @error('correo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Estado -->
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select id="is_active" 
                                        name="is_active" 
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('is_active') border-red-500 @enderror"
                                        style="color: #000 !important; background: #fff !important;">
                                    <option value="1" {{ old('is_active', $alumno->is_active) == 1 ? 'selected' : '' }}>
                                        ✅ Activo
                                    </option>
                                    <option value="0" {{ old('is_active', $alumno->is_active) == 0 ? 'selected' : '' }}>
                                        ❌ Inactivo
                                    </option>
                                </select>
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @if($alumno->sesiones_activas_count > 0)
                                    <div class="mt-1 p-2 bg-yellow-100 border border-yellow-400 text-yellow-700 text-sm rounded">
                                        ⚠️ No se puede desactivar: el alumno tiene sesiones activas
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">ℹ️ Información del Registro</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                <div>
                                    <strong>Creado:</strong> {{ $alumno->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div>
                                    <strong>Última modificación:</strong> {{ $alumno->updated_at->format('d/m/Y H:i') }}
                                </div>
                                <div>
                                    <strong>Total sesiones:</strong> {{ $alumno->sesiones_count ?? 0 }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="mt-8 flex items-center justify-between">
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                                    💾 Guardar Cambios
                                </button>
                                <a href="{{ route('alumnos.show', $alumno->id) }}" 
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                    ❌ Cancelar
                                </a>
                            </div>
                            
                            <!-- Botones adicionales -->
                            <div class="flex space-x-2">
                                @if($alumno->qr_image_path)
                                    <form method="POST" action="{{ route('alumnos.regenerar-qr', $alumno->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm"
                                                onclick="return confirm('¿Regenerar el código QR?')">
                                            🔄 Regenerar QR
                                        </button>
                                    </form>
                                @endif
                                
                                @if($alumno->sesiones_activas_count == 0)
                                    <form method="POST" action="{{ route('alumnos.destroy', $alumno->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm"
                                                onclick="return confirm('¿Estás seguro de eliminar este alumno? Esta acción no se puede deshacer.')">
                                            🗑️ Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Formatear RUT mientras se escribe
            const rutInput = document.getElementById('rut_dni');
            rutInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\dkK]/g, '');
                if (value.length > 1) {
                    value = value.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '-' + value.slice(-1);
                }
                e.target.value = value;
            });

            // Formatear NPI mientras se escribe
            const npiInput = document.getElementById('npi');
            npiInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                if (value.length > 7) {
                    value = value.slice(0, 7) + '-' + value.slice(7, 8);
                }
                e.target.value = value;
            });

            // Validar antes de enviar
            document.querySelector('form').addEventListener('submit', function(e) {
                const npi = npiInput.value;
                const npiPattern = /^[0-9]{7}-[0-9]{1}$/;
                
                if (!npiPattern.test(npi)) {
                    e.preventDefault();
                    alert('El NPI debe tener el formato correcto: 1234567-8');
                    npiInput.focus();
                    return false;
                }
            });
        });
    </script>
</x-app-layout>