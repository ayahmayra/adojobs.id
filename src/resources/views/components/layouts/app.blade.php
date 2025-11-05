<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ mobileMenuOpen: false }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? site_name() }}</title>

        <!-- Favicon -->
        @if(site_favicon())
            <link rel="icon" type="image/x-icon" href="{{ site_favicon() }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ site_favicon() }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=jost:400,500,600,700" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Jost', sans-serif;
            }
        </style>

        {{-- Additional Styles --}}
        @stack('styles')
    </head>
    <body class="antialiased bg-gray-50">
        
        {{-- Header --}}
        <x-header />

        {{-- Page Heading --}}
        @isset($header)
            <div class="bg-white shadow">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </div>
        @endisset

        {{-- Page Content --}}
        <main class="min-h-screen">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <x-footer />

        {{-- Toast Notification Container --}}
        <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

        {{-- Additional Scripts --}}
        @stack('scripts')

        {{-- Toast Notification Script - Must be after stack to ensure it's available --}}
        <script>
            // Make showToast available globally
            (function() {
                window.showToast = function(message, type = 'success') {
                    const container = document.getElementById('toast-container');
                    if (!container) {
                        console.error('Toast container not found');
                        return;
                    }

                    const toast = document.createElement('div');
                    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
                    const icon = type === 'success' ? 
                        '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                        type === 'error' ?
                        '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>' :
                        '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>';

                    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 min-w-[300px] max-w-md animate-slide-in`;
                    toast.innerHTML = `
                        <div class="flex-shrink-0">
                            ${icon}
                        </div>
                        <p class="flex-1 text-sm font-medium">${message}</p>
                        <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    `;

                    container.appendChild(toast);

                    // Auto remove after 3 seconds
                    setTimeout(() => {
                        toast.style.animation = 'slide-out 0.3s ease-out';
                        setTimeout(() => {
                            if (toast.parentElement) {
                                toast.remove();
                            }
                        }, 300);
                    }, 3000);
                };
            })();
        </script>

        <style>
            @keyframes slide-in {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slide-out {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            .animate-slide-in {
                animation: slide-in 0.3s ease-out;
            }
        </style>

    </body>
</html>
