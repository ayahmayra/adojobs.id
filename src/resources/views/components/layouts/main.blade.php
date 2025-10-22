<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ mobileMenuOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? site_name() . ' - Jalan Pintas Menuju Karier dan Talenta Terbaik!' }}</title>

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

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-footer />

    {{-- Additional Scripts --}}
    @stack('scripts')

</body>
</html>

