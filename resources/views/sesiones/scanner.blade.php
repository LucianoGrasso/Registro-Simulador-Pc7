<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Scanner de Sesiones') }}
            </h2>
            <div class="text-sm text-gray-600">
                Usuario: {{ auth()->user()->name }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Estado del sistema -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-red-100 p-4 rounded-lg border">
                    <div class="flex items-center">
                        <div class="text-gray-600 text-2xl font-bold" id="sesiones-activas-count">
                            {{ $sesionesActivas->count() }}
                        </div>
                        <div class="ml-3 text-gray-800">
                            <div class="text-sm font-medium">Sesiones Activas</div>
                        </div>
                    </div>
                </div>
                <div class="bg-green-100 p-4 rounded-lg border">
                    <div class="flex items-center">
                        <div class="text-green-600 text-2xl font-bold" id="sesiones-hoy-count">
                            {{ App\Models\Sesion::whereDate('fecha', today())->count() }}
                        </div>
                        <div class="ml-3 text-green-800">
                            <div class="text-sm font-medium">Sesiones Hoy</div>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg border">
                    <div class="flex items-center">
                        <div class="text-yellow-600 text-sm font-bold" id="ultima-actualizacion">
                            {{ now()->format('H:i:s') }}
                        </div>
                        <div class="ml-3 text-yellow-800">
                            <div class="text-sm font-medium">Última actualización</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Panel de Scanner -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Identificación de Alumno
                        </h3>
                        
                        <form id="scanner-form" class="space-y-4">
                            @csrf
                            
                            <!-- Toggle entre manual y cámara -->
                            <div class="flex space-x-2 mb-4">
                                <button type="button" id="btn-manual" class="flex-1 py-2 px-4 bg-blue-500 text-white rounded font-medium">
                                    ⌨️ Manual
                                </button>
                                <button type="button" id="btn-camera" class="flex-1 py-2 px-4 bg-gray-300 text-gray-700 rounded font-medium">
                                    📷 Cámara
                                </button>
                            </div>

                            <!-- Modo Manual -->
                            <div id="modo-manual">
                                <div>
                                    <label for="npi" class="block text-sm font-medium text-gray-700 mb-3">
                                        NPI del Alumno
                                    </label>
                                    <input type="text" 
                                           id="npi" 
                                           name="npi" 
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-lg font-mono"
                                           placeholder="Ingresa el NPI (Con o sin guión)"
                                           autocomplete="off"
                                           style="color: #000 !important; background: #fff !important;">
                                </div>
                            </div>

                            <!-- Modo Cámara -->
                            <div id="modo-camera" class="hidden">
                                <div class="text-center">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Scanner de Código QR
                                    </label>
                                    <!-- Video para la cámara -->
                                    <video id="qr-video" class="w-full max-w-lg mx-auto border-2 border-dashed border-gray-300 rounded-lg"></video>
                                    
                                    <!-- Controles de cámara -->
                                    <div class="mt-4 space-x-2">
                                        <button type="button" id="start-camera" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                            ▶️ Iniciar Cámara
                                        </button>
                                        <button type="button" id="stop-camera" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm hidden">
                                            ⏹️ Detener Cámara
                                        </button>
                                    </div>
                                    
                                    <!-- Estado del scanner -->
                                    <div id="scanner-status" class="mt-2 text-sm text-gray-600">
                                        Haz clic en "Iniciar Cámara" para comenzar a escanear
                                    </div>

                                    <!-- NPI detectado -->
                                    <div id="npi-detectado" class="mt-2 hidden">
                                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                            <strong>NPI Detectado:</strong> <span id="npi-valor" class="font-mono"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                             <!-- Tipo de Práctica (Checkboxes) -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Tipo de Práctica
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <!-- Práctica en seco -->
                                <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" 
                                           name="actividades[]" 
                                           value="Práctica en seco"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">🎭 Práctica en seco</span>
                                        <span class="block text-xs text-gray-500">Sin software de simulación</span>
                                    </span>
                                </label>

                                <!-- Emergencia en vuelo -->
                                <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" 
                                           name="actividades[]" 
                                           value="Emergencia en vuelo"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">🚨 Emergencia en vuelo</span>
                                        <span class="block text-xs text-gray-500">Procedimientos de emergencia</span>
                                    </span>
                                </label>

                                <!-- Trabajo en pista -->
                                <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" 
                                           name="actividades[]" 
                                           value="Trabajo en pista"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">🛫 Trabajo en pista</span>
                                        <span class="block text-xs text-gray-500">Despegue y aterrizaje</span>
                                    </span>
                                </label>

                                <!-- Acrobacias -->
                                <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" 
                                           name="actividades[]" 
                                           value="Acrobacias"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">✈️ Acrobacias</span>
                                        <span class="block text-xs text-gray-500">Maniobras acrobáticas</span>
                                    </span>
                                </label>

                                <!-- Navegación -->
                                <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" 
                                           name="actividades[]" 
                                           value="Navegación"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">🧭 Navegación</span>
                                        <span class="block text-xs text-gray-500">Vuelo por instrumentos y navegación</span>
                                    </span>
                                </label>

                                <!-- Vuelo instrumental -->
                                <label class="flex items-center p-3 border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" 
                                           name="actividades[]" 
                                           value="Vuelo instrumental"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="ml-3 text-sm text-gray-700">
                                        <span class="font-medium">📡 Vuelo instrumental</span>
                                        <span class="block text-xs text-gray-500">IFR y aproximaciones</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                            
                            <button type="submit" 
                                    id="btn-procesar"
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span id="btn-text">🚀 Iniciar Simulación</span>
                                <span id="btn-loading" class="hidden flex items-center">
                                    <svg class="animate-spin mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Procesando...
                                </span>
                            </button>
                        </form>
                        
                        <!-- Resultado -->
                        <div id="resultado" class="mt-4 hidden">
                            <div id="resultado-content" class="p-4 rounded-lg"></div>
                        </div>
                    </div>
                </div>

                <!-- Reemplaza la sección "Panel de Sesiones Activas" completa por esto: -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                ⏱️ Sesiones Activas
            </h3>
            
            <div id="sesiones-activas-lista" class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($sesionesActivas as $sesion)
                    <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-3">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">
                                    {{ $sesion->alumno->nombre_completo }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    NPI: <span class="font-mono">{{ $sesion->alumno->npi }}</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Inicio: {{ $sesion->hora_inicio->format('H:i') }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ Str::limit($sesion->actividad, 60) }}
                                </div>
                            </div>
                            <div class="text-right flex flex-col items-end space-y-2">
                                <div class="text-sm font-medium text-yellow-700">
                                    {{ $sesion->tiempo_transcurrido }}
                                </div>
                                @if($sesion->necesitaAtencion())
                                    <div class="text-xs text-red-600 font-medium">
                                        ⚠️ Necesita atención
                                    </div>
                                @endif
                                <!-- BOTÓN DE FINALIZACIÓN -->
                                <button onclick="confirmarFinalizacion({{ $sesion->id }}, '{{ $sesion->alumno->nombre_completo }}')" 
                                        class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded-md font-medium transition-colors">
                                    🏁 Finalizar
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-8">
                        <div class="text-4xl mb-2">✈️</div>
                        <div>No hay sesiones activas</div>
                        <div class="text-sm">El simulador esta disponible</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>


    <script>
        let qrScanner = null;
        let currentMode = 'manual';

        // Funciones para finalización directa
    function confirmarFinalizacion(sesionId, nombreAlumno) {
        if (confirm(`¿Estás seguro de que deseas finalizar la sesión de ${nombreAlumno}?`)) {
            finalizarSesionDirecta(sesionId);
        }
    }

    function finalizarSesionDirecta(sesionId) {
    console.log('Intentando finalizar sesión:', sesionId);
    
    const boton = document.querySelector(`button[onclick*="${sesionId}"]`);
    const sesionDiv = boton.closest('.border.border-yellow-200'); // El contenedor de la sesión
    const textoOriginal = boton.innerHTML;
    boton.innerHTML = '⏳ Finalizando...';
    boton.disabled = true;
    
    fetch(`/sesiones/${sesionId}/finalizar-directa`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            mostrarResultadoFinalizacion(data);
            
            // Cambiar el botón a "Finalizada" temporalmente
            boton.innerHTML = '✅ Finalizada';
            boton.classList.remove('bg-red-600', 'hover:bg-red-700');
            boton.classList.add('bg-green-600');
            
            // Eliminar la sesión después de 2 segundos
            setTimeout(() => {
                sesionDiv.style.transition = 'opacity 0.5s, transform 0.5s';
                sesionDiv.style.opacity = '0';
                sesionDiv.style.transform = 'scale(0.95)';
                
                setTimeout(() => {
                    sesionDiv.remove();
                    // Actualizar contador
                    actualizarContadorSesiones();
                }, 500);
            }, 2000);
            
        } else {
            alert('Error: ' + data.message);
            boton.innerHTML = textoOriginal;
            boton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        alert('Error de conexión: ' + error.message);
        boton.innerHTML = textoOriginal;
        boton.disabled = false;
    });
}

// Función para actualizar solo el contador
function actualizarContadorSesiones() {
    const sesionesActivas = document.querySelectorAll('#sesiones-activas-lista .border-yellow-200').length;
    document.getElementById('sesiones-activas-count').textContent = sesionesActivas;
    
    // Si no hay sesiones, mostrar mensaje
    if (sesionesActivas === 0) {
        document.getElementById('sesiones-activas-lista').innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <div class="text-4xl mb-2">✈️</div>
                <div>No hay sesiones activas</div>
                <div class="text-sm">El simulador esta disponible</div>
            </div>
        `;
    }
}

    function mostrarResultadoFinalizacion(data) {
        const resultadoDiv = document.getElementById('resultado');
        const resultadoContent = document.getElementById('resultado-content');
        
        let html = `<div class="bg-blue-100 border border-blue-200 rounded-lg p-4">`;
        html += `<div class="flex items-start">`;
        html += `<div class="text-2xl mr-3">🏁</div>`;
        html += `<div>`;
        html += `<h4 class="font-medium text-blue-600">${data.alumno}</h4>`;
        html += `<p class="text-sm text-gray-600 mt-1">${data.message}</p>`;
        html += `<div class="text-sm text-gray-500 mt-2">`;
        html += `Duración: ${data.duracion}<br>`;
        html += `${data.hora_inicio} - ${data.hora_fin}`;
        html += `</div>`;
        html += `</div></div></div>`;
        
        resultadoContent.innerHTML = html;
        resultadoDiv.classList.remove('hidden');
        
        // Ocultar después de 8 segundos
        setTimeout(() => {
            resultadoDiv.classList.add('hidden');
        }, 8000);
    }



        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('scanner-form');
            const npiInput = document.getElementById('npi');
            const resultadoDiv = document.getElementById('resultado');
            const resultadoContent = document.getElementById('resultado-content');
            const btnProcesar = document.getElementById('btn-procesar');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            
            // Elementos del scanner
            const btnManual = document.getElementById('btn-manual');
            const btnCamera = document.getElementById('btn-camera');
            const modoManual = document.getElementById('modo-manual');
            const modoCamera = document.getElementById('modo-camera');
            const video = document.getElementById('qr-video');
            const startCamera = document.getElementById('start-camera');
            const stopCamera = document.getElementById('stop-camera');
            const scannerStatus = document.getElementById('scanner-status');
            const npiDetectado = document.getElementById('npi-detectado');
            const npiValor = document.getElementById('npi-valor');

            // Cambiar entre modos
            btnManual.addEventListener('click', function() {
                switchToMode('manual');
            });

            btnCamera.addEventListener('click', function() {
                switchToMode('camera');
            });

            function switchToMode(mode) {
                currentMode = mode;
                
                if (mode === 'manual') {
                    // Activar modo manual
                    btnManual.className = 'flex-1 py-2 px-4 bg-blue-500 text-white rounded font-medium';
                    btnCamera.className = 'flex-1 py-2 px-4 bg-gray-300 text-gray-700 rounded font-medium';
                    modoManual.classList.remove('hidden');
                    modoCamera.classList.add('hidden');
                    npiInput.focus();
                    
                    // Detener cámara si está activa
                    if (qrScanner) {
                        stopQrScanner();
                    }
                } else {
                    // Activar modo cámara
                    btnCamera.className = 'flex-1 py-2 px-4 bg-blue-500 text-white rounded font-medium';
                    btnManual.className = 'flex-1 py-2 px-4 bg-gray-300 text-gray-700 rounded font-medium';
                    modoCamera.classList.remove('hidden');
                    modoManual.classList.add('hidden');
                }
            }

            // Iniciar scanner QR
            startCamera.addEventListener('click', async function() {
                try {
                    scannerStatus.textContent = 'Iniciando cámara...';
                    
                    // Importar QrScanner dinámicamente
                    if (!window.QrScanner) {
                        throw new Error('QrScanner no está disponible');
                    }

                    qrScanner = new window.QrScanner(video, result => {
                        console.log('QR detectado:', result);
                        
                        // Formatear NPI si es necesario
                        let npi = result.data;
                        
                        // Si son solo números de 8 dígitos, formatear como NPI
                        if (/^\d{8}$/.test(npi)) {
                            npi = npi.substring(0, 7) + '-' + npi.substring(7);
                        }
                        
                        // Mostrar NPI detectado
                        npiValor.textContent = npi;
                        npiDetectado.classList.remove('hidden');
                        
                        // Llenar el input oculto
                        npiInput.value = npi;
                        
                        // Actualizar estado
                        scannerStatus.textContent = '✅ QR detectado correctamente';
                        
                        // Opcional: detener scanner después de detectar
                        // stopQrScanner();
                        
                    }, {
                        returnDetailedScanResult: true,
                        highlightScanRegion: true,
                        highlightCodeOutline: true,
                    });

                    await qrScanner.start();
                    
                    startCamera.classList.add('hidden');
                    stopCamera.classList.remove('hidden');
                    scannerStatus.textContent = '📷 Cámara activa - Enfoca el código QR';
                    
                } catch (error) {
                    console.error('Error al iniciar cámara:', error);
                    scannerStatus.textContent = '❌ Error: ' + error.message;
                    
                    if (error.name === 'NotAllowedError') {
                        scannerStatus.textContent = '❌ Permiso de cámara denegado. Permite el acceso a la cámara.';
                    } else if (error.name === 'NotFoundError') {
                        scannerStatus.textContent = '❌ No se encontró cámara en el dispositivo.';
                    }
                }
            });

            // Detener scanner
            stopCamera.addEventListener('click', function() {
                stopQrScanner();
            });

            function stopQrScanner() {
                if (qrScanner) {
                    qrScanner.stop();
                    qrScanner.destroy();
                    qrScanner = null;
                }
                
                startCamera.classList.remove('hidden');
                stopCamera.classList.add('hidden');
                scannerStatus.textContent = 'Cámara detenida';
                npiDetectado.classList.add('hidden');
            }

            // Auto-focus en manual
            if (currentMode === 'manual') {
                npiInput.focus();
            }

            // Envío del formulario
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Obtener NPI (desde input o desde scanner)
                let npiValue = npiInput.value.trim();
                
                // Validación básica
                if (!npiValue) {
                    mostrarError('Por favor ingresa o escanea el NPI del alumno');
                    return;
                }
                
                // Validar que haya al menos una actividad seleccionada
                const checkboxes = document.querySelectorAll('input[name="actividades[]"]:checked');
                if (checkboxes.length === 0) {
                    mostrarError('Por favor selecciona al menos un tipo de práctica');
                    return;
                }

                // Construir texto de actividad desde los checkboxes
                const actividades = Array.from(checkboxes).map(cb => cb.value);
                let actividadTexto = actividades.join(', ');

                const observaciones = document.getElementById('observaciones')?.value.trim();
                if (observaciones) {
                    actividadTexto += ` - ${observaciones}`;
                }
                
                // Mostrar loading
                btnText.classList.add('hidden');
                btnLoading.classList.remove('hidden');
                btnProcesar.disabled = true;
                
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('npi', npiValue);
                formData.append('actividad', actividadTexto);  // ← SOLO ESTA LÍNEA, ELIMINA TODO LO DE ARRIBA QUE ESTÁ DUPLICADO
                
                fetch('{{ route("sesiones.procesar-qr") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarResultado(data);
                        form.reset();
                        npiInput.value = '';
                        npiDetectado.classList.add('hidden');
                        
                        if (currentMode === 'manual') {
                            npiInput.focus();
                        }
                        
                        actualizarSesionesActivas();
                    } else {
                        mostrarError(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarError('Error de conexión. Intenta de nuevo.');
                })
                .finally(() => {
                    // Ocultar loading
                    btnText.classList.remove('hidden');
                    btnLoading.classList.add('hidden');
                    btnProcesar.disabled = false;
                });
            });

            // Resto de funciones (mostrarResultado, mostrarError, etc.)
            function mostrarResultado(data) {
                let html = '';
                let bgClass = data.action === 'iniciar' ? 'bg-green-100 border-green-200' : 'bg-blue-100 border-blue-200';
                let iconClass = data.action === 'iniciar' ? 'text-green-600' : 'text-blue-600';
                let icon = data.action === 'iniciar' ? '✅' : '🏁';
                
                html += `<div class="${bgClass} border rounded-lg p-4">`;
                html += `<div class="flex items-start">`;
                html += `<div class="text-2xl mr-3">${icon}</div>`;
                html += `<div>`;
                html += `<h4 class="font-medium ${iconClass}">${data.alumno}</h4>`;
                html += `<p class="text-sm text-gray-600 mt-1">${data.message}</p>`;
                
                if (data.action === 'iniciar') {
                    html += `<div class="text-sm text-gray-500 mt-2">Hora de inicio: ${data.hora_inicio}</div>`;
                } else {
                    html += `<div class="text-sm text-gray-500 mt-2">`;
                    html += `Duración: ${data.duracion}<br>`;
                    html += `${data.hora_inicio} - ${data.hora_fin}`;
                    html += `</div>`;
                }
                
                html += `</div></div></div>`;
                
                resultadoContent.innerHTML = html;
                resultadoDiv.classList.remove('hidden');
                
                // Ocultar después de 8 segundos
                setTimeout(() => {
                    resultadoDiv.classList.add('hidden');
                }, 8000);
            }

            function mostrarError(mensaje) {
                let html = `<div class="bg-red-100 border border-red-200 rounded-lg p-4">`;
                html += `<div class="flex items-center">`;
                html += `<div class="text-red-600 text-xl mr-3">❌</div>`;
                html += `<div class="text-red-800">${mensaje}</div>`;
                html += `</div></div>`;
                
                resultadoContent.innerHTML = html;
                resultadoDiv.classList.remove('hidden');
                
                setTimeout(() => {
                    resultadoDiv.classList.add('hidden');
                }, 5000);
            }

            function actualizarSesionesActivas() {
                fetch('{{ route("sesiones.activas-ajax") }}')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('sesiones-activas-count').textContent = data.count;
                        document.getElementById('ultima-actualizacion').textContent = new Date().toLocaleTimeString();
                        
                        // Actualizar lista de sesiones activas
                        const lista = document.getElementById('sesiones-activas-lista');
                        let html = '';
                        
                        if (data.sesiones.length === 0) {
                            html = `
                                <div class="text-center text-gray-500 py-8">
                                    <div class="text-4xl mb-2">✈️</div>
                                    <div>No hay sesiones activas</div>
                                    <div class="text-sm">El simulador esta disponible</div>
                                </div>
                            `;
                        } else {
                            data.sesiones.forEach(sesion => {
                                html += `
                                    <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-3">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900">${sesion.alumno.nombre_completo}</div>
                                                <div class="text-sm text-gray-600">NPI: <span class="font-mono">${sesion.alumno.npi}</span></div>
                                                <div class="text-sm text-gray-600">Inicio: ${sesion.hora_inicio}</div>
                                                <div class="text-xs text-gray-500 mt-1">${sesion.actividad.substring(0, 60)}</div>
                                            </div>
                                            <div class="text-right flex flex-col items-end space-y-2">
                                                <div class="text-sm font-medium text-yellow-700">${sesion.tiempo_transcurrido}</div>
                                                ${sesion.necesita_atencion ? '<div class="text-xs text-red-600 font-medium">⚠️ Necesita atención</div>' : ''}
                                                <button onclick="confirmarFinalizacion(${sesion.id}, '${sesion.alumno.nombre_completo}')" 
                                                        class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded-md font-medium transition-colors">
                                                    🏁 Finalizar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                        
                        lista.innerHTML = html;
                    })
                    .catch(error => console.error('Error actualizando sesiones:', error));
            }

            // Actualizar cada 30 segundos
            setInterval(actualizarSesionesActivas, 30000);

            // Limpiar scanner al salir
            window.addEventListener('beforeunload', function() {
                if (qrScanner) {
                    qrScanner.destroy();
                }
            });
        });
    </script>
</x-app-layout>