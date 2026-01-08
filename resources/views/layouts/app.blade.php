<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
        
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-300">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-grow">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-gray-800 border-t border-gray-700 mt-auto mt-3">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <!-- Logo y nombre del sistema -->
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('images/VueloPC7.png') }}" 
                                 alt="PC-7" 
                                 class="w-20 h-16 object-contain">
                            <div>
                                <p class="text-white font-semibold text-sm">Sistema de Registro Simulador PC-7</p>
                                <p class="text-gray-400 text-xs">Armada de Chile</p>
                            </div>
                        </div>

                        <!-- Información del desarrollador -->
                        <div class="text-center md:text-right">
                            <p class="text-gray-300 text-sm">
                                Desarrollado por <span class="text-red-400 font-semibold">Luciano Grasso</span>
                            </p>
                            <p class="text-gray-400 text-xs">
                                Encargado del Simulador
                            </p>
                        </div>
                    </div>

                    <!-- Línea separadora -->
                    <div class="mt-4 pt-4 border-t border-red-400">
                        <p class="text-center text-gray-500 text-xs">
                            Versión 1.5
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>