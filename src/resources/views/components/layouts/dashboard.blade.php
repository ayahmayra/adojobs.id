<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false, mobileMenuOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? site_name() }} - Dashboard</title>

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

    {{-- Top Navigation --}}
    <nav class="fixed top-0 z-30 w-full bg-white border-b border-gray-200 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    {{-- Logo --}}
                    <div class="flex flex-shrink-0 items-center">
                        <a href="/" class="flex items-center">
                            @if(site_logo())
                                <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-auto h-8">
                                <span class="ml-2 text-xl font-bold text-gray-900">{{ site_name() }}</span>
                            @else
                                <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="ml-2 text-xl font-bold text-gray-900">{{ site_name() }}</span>
                            @endif
                        </a>
                    </div>

                    {{-- Mobile menu button --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="ml-3 text-gray-500 lg:hidden">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                {{-- Right side --}}
                <div class="flex items-center space-x-4">
                    {{-- Contact Admin Link (for Employer & Seeker) --}}
                    @if(Auth::user()->isSeeker() || Auth::user()->isEmployer())
                        <form action="{{ route('messages.start') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="contact_admin" value="1">
                            <button type="submit" 
                                    class="flex items-center px-3 py-2 text-sm font-medium text-purple-600 bg-purple-50 rounded-lg transition hover:bg-purple-100"
                                    title="Hubungi Admin">
                                <svg class="mr-1.5 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span class="hidden md:inline">Hubungi Admin</span>
                            </button>
                        </form>
                    @endif

                    {{-- Messages Notification --}}
                    @if(Auth::user()->isSeeker() || Auth::user()->isEmployer() || Auth::user()->isAdmin())
                        @php
                            if (Auth::user()->isAdmin()) {
                                $unreadMessagesCount = \App\Models\Conversation::active()
                                    ->forAdmin(Auth::user()->id)
                                    ->unread()
                                    ->count();
                            } else {
                                $unreadMessagesCount = \App\Models\Conversation::active()
                                    ->{Auth::user()->isSeeker() ? 'forSeeker' : 'forEmployer'}(Auth::user()->{Auth::user()->isSeeker() ? 'seeker' : 'employer'}->id)
                                    ->unread()
                                    ->count();
                            }
                        @endphp
                        <a href="{{ route('messages.index') }}" class="relative text-gray-500 transition hover:text-indigo-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            @if($unreadMessagesCount > 0)
                                <span class="flex absolute -top-1 -right-1 justify-center items-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">
                                    {{ $unreadMessagesCount > 9 ? '9+' : $unreadMessagesCount }}
                                </span>
                            @endif
                        </a>
                    @endif

                    {{-- User Menu --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600">
                            <div class="hidden text-right sm:block">
                                <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</div>
                            </div>
                            <img src="{{ Auth::user()->avatar_url }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="object-cover w-10 h-10 rounded-full border-2 border-gray-200 transition hover:border-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 z-50 py-2 mt-2 w-48 bg-white rounded-lg shadow-lg"
                             style="display: none;">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                My Profile
                            </a>
                            <a href="/" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                View Site
                            </a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 w-full text-left text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Sidebar & Main Content --}}
    <div class="flex pt-16">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-20 mt-16 w-64 bg-white shadow-lg transition-transform duration-200 ease-in-out transform lg:mt-0 lg:translate-x-0 lg:static lg:inset-0"
               :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <nav class="overflow-y-auto px-4 py-6 space-y-2 h-full">
                {{ $sidebar }}
            </nav>
        </aside>

        {{-- Overlay for mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             class="fixed inset-0 z-10 bg-black bg-opacity-50 lg:hidden"
             style="display: none;"></div>

        {{-- Main Content --}}
        <main class="overflow-x-hidden flex-1">
            {{-- Page Header --}}
            @isset($header)
            <div class="mb-6 bg-white shadow">
                <div class="px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </div>
            @endisset

            {{-- Page Content --}}
            <div class="px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Additional Scripts --}}
    @stack('scripts')

</body>
</html>

