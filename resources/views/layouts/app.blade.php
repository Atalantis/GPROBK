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

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Toast Notifications Container -->
        <div id="toast-container" class="fixed top-5 right-5 z-50 space-y-2"></div>

        @stack('scripts')

        @auth
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.Echo.private('App.Models.User.{{ Auth::id() }}')
                    .notification((notification) => {
                        console.log(notification); // For debugging

                        const container = document.getElementById('toast-container');

                        const toast = document.createElement('div');
                        toast.className = 'max-w-xs bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg';
                        toast.innerHTML = `
                            <div class="p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="ml-3 w-0 flex-1 pt-0.5">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">${notification.title}</p>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">${notification.message}</p>
                                        ${notification.url ? `<a href="${notification.url}" class="mt-2 text-sm font-semibold text-blue-600 hover:text-blue-500">Voir</a>` : ''}
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex">
                                        <button onclick="this.closest('.max-w-xs').remove()" class="inline-flex text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Close</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;

                        container.appendChild(toast);

                        setTimeout(() => {
                            toast.remove();
                        }, 5000); // Remove toast after 5 seconds
                    });
            });
        </script>
        @endauth

        @livewireScripts
    </body>
</html>
