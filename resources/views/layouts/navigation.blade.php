<nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-700 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo/Título del Proyecto - MÁS A LA IZQUIERDA -->
                <div class="shrink-0 flex items-center group cursor-pointer pl-0 pr-3" style="min-width: 320px;">
                    <div class="relative">
                        <!-- Logo PC-7 -->
                        <img src="{{ asset('images/LogoPC7.png') }}" 
                             alt="Simulador PC-7" 
                             class="relative w-26 h-20 object-contain animate-bounce-slow">
                    </div>
                    
                    <div class="ml-4">
                        <h1 class="font-black text-3xl flex flex-col leading-tight">
                            <span class="bg-gradient-to-r from-red-400 via-red-500 to-red-600 bg-clip-text text-transparent 
                                         drop-shadow-[0_1px_2px_rgba(239,68,68,0.8)] 
                                         group-hover:drop-shadow-[0_2px_4px_rgba(239,68,68,0.9)]
                                         transition-all duration-300
                                         tracking-wide">
                                SIMULADOR
                            </span>
                            <span class="text-white text-xl font-bold tracking-widest 
                                         group-hover:text-red-400 
                                         transition-colors duration-300
                                         drop-shadow-md">
                                PC-7
                            </span>
                        </h1>
                    </div>
                </div>

                <!-- Separador vertical -->
                <div class="h-12 w-px bg-red-400 mx-4 self-center"></div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:flex items-center">
                    <!-- Dashboard -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                        class="inline-flex items-center gap-2 px-3 pt-1 pb-2 border-b-2 text-base font-semibold leading-5 transition duration-150 ease-in-out focus:outline-none
                               {{ request()->routeIs('dashboard') 
                                   ? 'border-red-400 text-white' 
                                   : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }}">
                        <span class="text-lg">🏠</span>
                        <span>{{ __('Dashboard') }}</span>
                    </x-nav-link>

                    <x-nav-link :href="route('sesiones.scanner')" :active="request()->routeIs('sesiones.scanner')"
                        class="inline-flex items-center gap-2 px-3 pt-1 pb-2 border-b-2 text-base font-semibold leading-5 transition duration-150 ease-in-out focus:outline-none
                               {{ request()->routeIs('sesiones.scanner') 
                                   ? 'border-red-400 text-white' 
                                   : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }}">
                        <span class="text-lg">📱</span>
                        <span>{{ __('Scanner') }}</span>
                    </x-nav-link>

                    <!-- Vuelos (Para todos) -->
                    <x-nav-link :href="route('vuelos.index')" :active="request()->routeIs('vuelos.index') || request()->routeIs('vuelos.show')"
                                class="inline-flex items-center gap-2 px-3 pt-1 pb-2 border-b-2 text-base font-semibold leading-5 transition duration-150 ease-in-out focus:outline-none
                                    {{ request()->routeIs('vuelos.index') || request()->routeIs('vuelos.show')
                                        ? 'border-red-400 text-white' 
                                        : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }}">
                        <span class="text-lg">✈️</span>
                        <span>{{ __('Vuelos') }}</span>
                    </x-nav-link>

                    @if(auth()->user()->isAdmin())
                        <!-- Alumnos -->
                        <x-nav-link :href="route('alumnos.index')" :active="request()->routeIs('alumnos.*')"
                            class="inline-flex items-center gap-2 px-3 pt-1 pb-2 border-b-2 text-base font-semibold leading-5 transition duration-150 ease-in-out focus:outline-none
                                   {{ request()->routeIs('alumnos.*') 
                                       ? 'border-red-400 text-white' 
                                       : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }}">
                            <span class="text-lg">🎓</span>
                            <span>{{ __('Alumnos') }}</span>
                        </x-nav-link>

                        <!-- NUEVO: Sesiones (Admin) -->
                        <x-nav-link :href="route('sesiones.index')" :active="request()->routeIs('sesiones.index') || request()->routeIs('sesiones.edit')"
                            class="inline-flex items-center gap-2 px-3 pt-1 pb-2 border-b-2 text-base font-semibold leading-5 transition duration-150 ease-in-out focus:outline-none
                                   {{ request()->routeIs('sesiones.index') || request()->routeIs('sesiones.edit')
                                       ? 'border-red-400 text-white' 
                                       : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }}">
                            <span class="text-lg">📋</span>
                            <span>{{ __('Sesiones') }}</span>
                        </x-nav-link>

                        <!-- Reportes -->
                        <x-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.*')"
                            class="inline-flex items-center gap-2 px-3 pt-1 pb-2 border-b-2 text-base font-semibold leading-5 transition duration-150 ease-in-out focus:outline-none
                                   {{ request()->routeIs('reportes.*') 
                                       ? 'border-red-400 text-white' 
                                       : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }}">
                            <span class="text-lg">📊</span>
                            <span>{{ __('Reportes') }}</span>
                        </x-nav-link>

                        <!-- Soporte -->
                        <x-nav-link :href="route('soporte.index')" :active="request()->routeIs('soporte.*')"
                            class="inline-flex items-center gap-2 px-3 pt-1 pb-2 border-b-2 text-base font-semibold leading-5 transition duration-150 ease-in-out focus:outline-none
                                   {{ request()->routeIs('soporte.*') 
                                       ? 'border-red-400 text-white' 
                                       : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }}">
                            <span class="text-lg">🛠️</span>
                            <span>{{ __('Soporte') }}</span>
                        </x-nav-link>
                    @else
                        <!-- Soporte operadores -->
                        <x-nav-link :href="route('soporte.create')" :active="request()->routeIs('soporte.*')"
                            class="inline-flex items-center gap-2 px-3 pt-1 pb-2 border-b-2 text-base font-semibold leading-5 transition duration-150 ease-in-out focus:outline-none
                                   {{ request()->routeIs('soporte.*') 
                                       ? 'border-red-400 text-white' 
                                       : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }}">
                            <span class="text-lg">🛠️</span>
                            <span>{{ __('Soporte') }}</span>
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                <!-- Modo Oscuro -->
                <button id="theme-toggle" type="button" 
                    class="text-gray-500 dark:text-gray-400 bg-gray-800 hover:bg-gray-700 dark:hover:bg-gray-700 focus:outline-none rounded-lg text-sm p-2.5 mr-2 !border !border-red-400">
                    
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 !text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 !text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <!-- Usuario y rol -->
                <div class="flex items-center gap-2">
                    <span class="text-xs px-3 py-1.5 rounded-full whitespace-nowrap {{ auth()->user()->isAdmin() ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                        {{ auth()->user()->isAdmin() ? '👑 Admin' : '👤 Operador' }}
                    </span>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 !border !border-red-400 text-sm leading-4 font-medium rounded-md text-white bg-gray-700 hover:bg-gray-600 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="shadow-xl rounded-md">
                            @if(auth()->user()->isAdmin())
                                <x-dropdown-link :href="route('profile.edit')" class="text-white hover:bg-gray-100">
                                    ⚙️ {{ __('Configuración') }}
                                </x-dropdown-link>
                            @else
                                <x-dropdown-link :href="route('operador.info')" class="text-white hover:bg-gray-100">
                                    👤 {{ __('Mi Información') }}
                                </x-dropdown-link>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="text-white hover:bg-gray-100"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    🚪 {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-900">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Dashboard -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                🏠 {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Scanner -->
            <x-responsive-nav-link :href="route('sesiones.scanner')" :active="request()->routeIs('sesiones.scanner')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                📱 {{ __('Scanner QR') }}
            </x-responsive-nav-link>

            @if(auth()->user()->isAdmin())
                <!-- Alumnos -->
                <x-responsive-nav-link :href="route('alumnos.index')" :active="request()->routeIs('alumnos.*')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                    🎓 {{ __('Gestión de Alumnos') }}
                </x-responsive-nav-link>

                <!-- NUEVO: Sesiones (Admin) -->
                <x-responsive-nav-link :href="route('sesiones.index')" :active="request()->routeIs('sesiones.index')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                    📋 {{ __('Gestión de Sesiones') }}
                </x-responsive-nav-link>

                <!-- Reportes -->
                <x-responsive-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.*')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                    📊 {{ __('Reportes') }}
                </x-responsive-nav-link>

                <!-- Soporte -->
                <x-responsive-nav-link :href="route('soporte.index')" :active="request()->routeIs('soporte.*')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                    🛠️ {{ __('Soporte') }}
                </x-responsive-nav-link>
            @else
                <!-- Soporte -->
                <x-responsive-nav-link :href="route('soporte.create')" :active="request()->routeIs('soporte.*')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                    🛠️ {{ __('Soporte') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
                <div class="text-xs mt-1">
                    <span class="px-2 py-1 rounded-full {{ auth()->user()->isAdmin() ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                        {{ auth()->user()->isAdmin() ? '👑 Administrador' : '👤 Operador' }}
                    </span>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                @if(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('alumnos.create')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                        ➕ {{ __('Nuevo Alumno') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                        ⚙️ {{ __('Configuración') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('operador.info')" class="text-gray-300 hover:text-white hover:bg-gray-700">
                        👤 {{ __('Mi Información') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-gray-300 hover:text-white hover:bg-gray-700">
                        🚪 {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-3px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 2s ease-in-out infinite;
    }
    .group:hover .drop-shadow-lg {
        filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.8));
    }
    </style>

    
    <!-- Script para actualizar contador de sesiones activas -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function actualizarContadorNav() {
                fetch('{{ route("sesiones.activas-ajax") }}')
                    .then(response => response.json())
                    .then(data => {
                        const contador = document.getElementById('nav-sesiones-count');
                        const indicador = document.getElementById('sesiones-activas-indicator');
                        
                        if (contador) {
                            contador.textContent = data.count;
                            
                            // Cambiar color según cantidad de sesiones
                            if (data.count > 0) {
                                indicador.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
                            } else {
                                indicador.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600';
                            }
                        }
                    })
                    .catch(error => console.log('Error actualizando contador nav:', error));
            }
            
            // Actualizar al cargar
            actualizarContadorNav();
            
            // Actualizar cada 30 segundos
            setInterval(actualizarContadorNav, 30000);
        });

        // Script para el tema oscuro
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Cambiar iconos según estado actual
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
            document.documentElement.classList.add('dark');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
            document.documentElement.classList.remove('dark');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    </script>
</nav>