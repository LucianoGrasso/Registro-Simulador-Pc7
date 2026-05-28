<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Título -->
    <div class="mb-6 text-center">
        <h2 id="llave-secreta" class="text-2xl font-bold text-center select-none cursor-default">
            Iniciar Sesión
        </h2>
        <p class="text-sm text-gray-600 mt-1">Sistema de Registro Simulador PC-7</p>
    </div>

    <!-- Credenciales de Acceso para Operadores -->
    <div class="mb-6 p-4 bg-blue-50 border-2 border-blue-300 rounded-lg">
        <h3 class="text-sm font-semibold text-blue-900 mb-2">
            🔑 Acceso para Operadores
        </h3>
        <div class="text-sm text-blue-800 space-y-1">
            <p><strong>Email:</strong> <code class="bg-blue-100 px-2 py-1 rounded font-mono text-xs">operador@simulador.local</code></p>
            <p><strong>Contraseña:</strong> <code class="bg-blue-100 px-2 py-1 rounded font-mono text-xs">operador123</code></p>
        </div>
        <p class="text-xs text-blue-600 mt-2">
            Estas credenciales son de acceso público para operadores del simulador
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center bg-red-500 hover:bg-red-600 focus:bg-red-700 active:bg-red-800">
                {{ __('Iniciar Sesión') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Buscamos nuestro "botón" secreto y los campos del formulario
            const llaveSecreta = document.getElementById('llave-secreta');
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password'); // Aquí está la variable

            if (llaveSecreta && emailInput && passInput) {
                // Cuando hagan doble clic, llenamos los datos
                llaveSecreta.addEventListener('dblclick', function() {
                    emailInput.value = 'instructor@simulador.local';
                    passInput.value = 'instructor123'; // ¡Ahora sí usamos el nombre correcto!
                    
                    // Efecto visual: parpadear el fondo en verde claro
                    emailInput.style.backgroundColor = '#dcfce3';
                    passInput.style.backgroundColor = '#dcfce3';
                    
                    // Quitar el verde después de 300ms
                    setTimeout(() => {
                        emailInput.style.backgroundColor = '';
                        passInput.style.backgroundColor = '';
                    }, 300);
                });
            }
        });
    </script>
</x-guest-layout>