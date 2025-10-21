<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ mobileMenuOpen: false }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'AdoJobs.id') }}</title>

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

        {{-- Additional Scripts --}}
        @stack('scripts')

    </body>
</html>
