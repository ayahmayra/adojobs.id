<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'AdoJobs.id' }}</title>

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
    @stack('styles')
</head>
<body class="antialiased bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    
    <div class="flex flex-col justify-center py-12 min-h-screen sm:px-6 lg:px-8">
        {{-- Logo --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="/" class="flex justify-center items-center mb-8">
                <svg class="w-12 h-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="ml-3 text-2xl font-bold text-gray-900">AdoJobs.id</span>
            </a>
        </div>

        {{-- Main Content Card --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-6 py-8 bg-white rounded-xl shadow-xl sm:px-10">
                {{ $slot }}
            </div>
        </div>

        {{-- Back to Home --}}
        <div class="mt-6 text-center">
            <a href="/" class="inline-flex items-center text-sm text-gray-600 transition hover:text-gray-900">
                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
