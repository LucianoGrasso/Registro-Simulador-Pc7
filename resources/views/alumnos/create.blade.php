<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Nuevo Alumno
            </h2>
            <a href="{{ route('alumnos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Volver a la Lista
            </a>
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

            

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">
                        ➕ Datos del Nuevo Alumno
                    </h3>

                    <form method="POST" action="{{ route('alumnos.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Nombre Completo -->
                            <div class="md:col-span-2">
                                <label for="nombre_completo" class="block text-sm font-medium text-gray-700">
                                    Nombre Completo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="nombre_completo" 
                                       name="nombre_completo" 
                                       value="{{ old('nombre_completo') }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('nombre_completo') border-red-500 @enderror"
                                       placeholder="Ej: Juan Carlos Pérez González"
                                       style="color: #000 !important; background: #fff !important;"
                                       required
                                       autofocus>
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
                                       value="{{ old('rut_dni') }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('rut_dni') border-red-500 @enderror"
                                       placeholder="12.345.678-9"
                                       style="color: #000 !important; background: #fff !important;"
                                       required>
                                @error('rut_dni')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    RUT chileno o DNI con formato oficial
                                </p>
                            </div>
                            
                            <!-- NPI -->
                            <div>
                                <label for="npi" class="block text-sm font-medium text-gray-700">
                                    NPI <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="npi" 
                                       name="npi" 
                                       value="{{ old('npi') }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 font-mono text-lg @error('npi') border-red-500 @enderror"
                                       placeholder="341725-9"
                                       pattern="[0-9]{6}-[0-9]{1}"
                                       maxlength="8"
                                       style="color: #000 !important; background: #fff !important;"
                                       required>
                                @error('npi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div class="mt-1 text-xs text-gray-500">
                                    <div>Formato: 6 dígitos + guión + 1 dígito verificador</div>
                                </div>
                            </div>
                            
                            <!-- Correo -->
                            <div class="md:col-span-2">
                                <label for="correo" class="block text-sm font-medium text-gray-700">
                                    Correo Electrónico
                                </label>
                                <input type="email" 
                                       id="correo" 
                                       name="correo" 
                                       value="{{ old('correo') }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('correo') border-red-500 @enderror"
                                       placeholder="alumno@email.com"
                                       style="color: #000 !important; background: #fff !important;">
                                @error('correo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    Opcional. Si se proporciona, debe ser único en el sistema
                                </p>
                            </div>
                        </div>

                        <!-- Preview del NPI -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg" id="npi-preview" style="display: none;">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Vista Previa del NPI</h4>
                            <div class="flex items-center space-x-4">
                                <div>
                                    <div class="text-xs text-gray-600">NPI formateado:</div>
                                    <div class="font-mono text-lg bg-blue-100 text-blue-800 px-3 py-1 rounded" id="npi-formatted"></div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-600">Contenido del QR:</div>
                                    <div class="font-mono text-lg bg-green-100 text-green-800 px-3 py-1 rounded" id="npi-qr-content"></div>
                                </div>
                                <div class="text-2xl">→</div>
                                <div class="text-4xl">📱</div>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="mt-8 flex items-center justify-between">
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline hover:opacity-90" style="background-color: #FE0000;">
                                    ➕ Crear Alumno
                                </button>
                                <a href="{{ route('alumnos.index') }}" 
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                    ❌ Cancelar
                                </a>
                            </div>
                            
                            <div class="text-sm text-gray-500">
                                <span class="text-red-500">*</span> Campos obligatorios
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rutInput = document.getElementById('rut_dni');
            const npiInput = document.getElementById('npi');
            const npiPreview = document.getElementById('npi-preview');
            const npiFormatted = document.getElementById('npi-formatted');
            const npiQrContent = document.getElementById('npi-qr-content');

            // Formatear RUT mientras se escribe
            rutInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\dkK]/g, '');
                if (value.length > 1) {
                    // Formatear como RUT chileno
                    const rutDigits = value.slice(0, -1);
                    const verifier = value.slice(-1);
                    const formattedRut = rutDigits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    e.target.value = formattedRut + '-' + verifier;
                }
            });

            // Formatear NPI y mostrar preview
            npiInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d-]/g, ''); // Permitir dígitos y guión
                
                // Remover guiones extra y mantener solo uno
                const parts = value.split('-');
                if (parts.length > 2) {
                    value = parts[0] + '-' + parts.slice(1).join('');
                }
                
                // Si el usuario escribió el guión manualmente, respetarlo
                if (value.includes('-')) {
                    const dashIndex = value.indexOf('-');
                    let beforeDash = value.substring(0, dashIndex).replace(/[^\d]/g, '');
                    let afterDash = value.substring(dashIndex + 1).replace(/[^\d]/g, '');
                    
                    // Limitar partes - 6 dígitos antes del guión, 1 después
                    if (beforeDash.length > 6) beforeDash = beforeDash.slice(0, 6);
                    if (afterDash.length > 1) afterDash = afterDash.slice(0, 1);
                    
                    if (beforeDash.length > 0) {
                        value = beforeDash + (afterDash.length > 0 || value.endsWith('-') ? '-' + afterDash : '');
                    } else {
                        value = '';
                    }
                } else {
                    // Auto-formatear si escriben solo números
                    let numbers = value.replace(/[^\d]/g, '');
                    if (numbers.length > 7) {
                        numbers = numbers.slice(0, 7);
                    }
                    
                    if (numbers.length > 6) {
                        value = numbers.slice(0, 6) + '-' + numbers.slice(6);
                    } else {
                        value = numbers;
                    }
                }
                
                e.target.value = value;
                
                // Mostrar preview si está completo
                if (/^[0-9]{6}-[0-9]{1}$/.test(value)) {
                    npiPreview.style.display = 'block';
                    npiFormatted.textContent = value;
                    npiQrContent.textContent = value.replace('-', ''); // Sin guión para QR
                } else {
                    npiPreview.style.display = 'none';
                }
            });

            // Validar formato NPI antes de enviar
            document.querySelector('form').addEventListener('submit', function(e) {
                const npi = npiInput.value.trim();
                const npiPattern = /^[0-9]{6}-[0-9]{1}$/;
                
                if (!npiPattern.test(npi)) {
                    e.preventDefault();
                    alert('El NPI debe tener exactamente el formato: 341725-9 (6 dígitos, guión, 1 dígito)');
                    npiInput.focus();
                    npiInput.select();
                    return false;
                }

                // Verificar que no sea todo ceros
                const npiNumbers = npi.replace('-', '');
                if (npiNumbers === '0000000') {
                    e.preventDefault();
                    alert('El NPI no puede ser todo ceros');
                    npiInput.focus();
                    npiInput.select();
                    return false;
                }
            });

            // Auto-completar ejemplos (para testing)
            document.addEventListener('keydown', function(e) {
                // Ctrl + Shift + E para ejemplo
                if (e.ctrlKey && e.shiftKey && e.key === 'E') {
                    document.getElementById('nombre_completo').value = 'Ejemplo Estudiante Prueba';
                    document.getElementById('rut_dni').value = '11.111.111-1';
                    document.getElementById('npi').value = '341725-9';
                    document.getElementById('correo').value = 'ejemplo@test.com';
                    
                    // Disparar evento input para mostrar preview
                    npiInput.dispatchEvent(new Event('input'));
                }
            });
        });
    </script>
</x-app-layout>