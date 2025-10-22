{{-- Header Component --}}
<header class="sticky top-0 z-50 bg-white shadow-sm">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            {{-- Logo --}}
            <div class="flex flex-shrink-0 items-center">
                <a href="/" class="flex items-center">
                    @if(site_logo())
                        <img src="{{ site_logo() }}" alt="{{ site_name() }}" class="w-auto h-10">
                        <span class="ml-2 text-2xl font-bold text-gray-900">{{ site_name() }}</span>
                    @else
                        <svg class="w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-2 text-2xl font-bold text-gray-900">{{ site_name() }}</span>
                    @endif
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="hidden space-x-8 md:flex">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} font-medium">
                    Beranda
                </a>
                <a href="{{ route('jobs.index') }}" class="{{ request()->routeIs('jobs.*') ? 'text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} font-medium">
                    Lowongan
                </a>
                <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} font-medium">
                    Kategori
                </a>
                <a href="{{ route('employers.index') }}" class="{{ request()->routeIs('employers.*') ? 'text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} font-medium">Rekruter</a>
                <a href="{{ route('seekers.index') }}" class="{{ request()->routeIs('seekers.*') ? 'text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} font-medium">Kandidat</a>
                <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }} font-medium">Artikel</a>
            </nav>

            {{-- Auth Buttons --}}
            <div class="hidden items-center space-x-4 md:flex">
                @auth
                    {{-- Dashboard Button --}}
                    @if(Auth::user()->isSeeker())
                        <a href="{{ route('seeker.dashboard') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg transition hover:bg-indigo-100">
                            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                            </svg>
                            Dashboard
                        </a>
                    @elseif(Auth::user()->isEmployer())
                        <a href="{{ route('employer.dashboard') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg transition hover:bg-indigo-100">
                            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                            </svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg transition hover:bg-indigo-100">
                            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                            </svg>
                            Dashboard
                        </a>
                    @endif

                    {{-- Messages Notification --}}
                    @if(Auth::user()->isSeeker() || Auth::user()->isEmployer())
                        @php
                            $unreadMessagesCount = \App\Models\Conversation::active()
                                ->{Auth::user()->isSeeker() ? 'forSeeker' : 'forEmployer'}(Auth::user()->{Auth::user()->isSeeker() ? 'seeker' : 'employer'}->id)
                                ->unread()
                                ->count();
                        @endphp
                        <a href="{{ route('messages.index') }}" class="relative text-gray-700 transition hover:text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            @if($unreadMessagesCount > 0)
                                <span class="flex absolute -top-1 -right-1 justify-center items-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">
                                    {{ $unreadMessagesCount > 9 ? '9+' : $unreadMessagesCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                    
                    {{-- User Dropdown untuk desktop --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 font-medium text-gray-700 hover:text-indigo-600 group">
                            <img src="{{ Auth::user()->avatar_url }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="object-cover w-10 h-10 rounded-full border-2 border-transparent transition group-hover:border-indigo-600">
                            <span class="hidden lg:block">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 z-50 py-2 mt-2 w-48 bg-white rounded-lg shadow-lg"
                             style="display: none;">
                            @if(Auth::user()->isSeeker())
                                <a href="{{ route('seeker.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    Dashboard
                                </a>
                            @elseif(Auth::user()->isEmployer())
                                <a href="{{ route('employer.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    Dashboard
                                </a>
                            @endif
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                Profil
                            </a>
                            <hr class="my-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 w-full text-left text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="font-medium text-gray-700 hover:text-indigo-600">
                        Masuk / Daftar
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 font-medium text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                        Pasang Lowongan
                    </a>
                @endauth
            </div>

            {{-- Mobile Right Side --}}
            <div class="flex items-center space-x-3 md:hidden">
                {{-- Dashboard Button (Mobile) - Always Visible --}}
                @auth
                    @if(Auth::user()->isSeeker())
                        <a href="{{ route('seeker.dashboard') }}" class="inline-flex items-center px-3 py-2 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg transition hover:bg-indigo-100">
                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                            </svg>
                            Dashboard
                        </a>
                    @elseif(Auth::user()->isEmployer())
                        <a href="{{ route('employer.dashboard') }}" class="inline-flex items-center px-3 py-2 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg transition hover:bg-indigo-100">
                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                            </svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg transition hover:bg-indigo-100">
                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                            </svg>
                            Dashboard
                        </a>
                    @endif
                @endauth
                
                {{-- Mobile Menu Button --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" class="border-t border-gray-200 md:hidden" style="display: none;">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block font-medium text-gray-700 hover:text-indigo-600">Beranda</a>
            <a href="{{ route('jobs.index') }}" class="block font-medium text-gray-700 hover:text-indigo-600">Lowongan</a>
            <a href="{{ route('categories.index') }}" class="block font-medium text-gray-700 hover:text-indigo-600">Kategori</a>
            <a href="{{ route('employers.index') }}" class="block font-medium text-gray-700 hover:text-indigo-600">Rekruter</a>
            <a href="{{ route('seekers.index') }}" class="block font-medium text-gray-700 hover:text-indigo-600">Kandidat</a>
            <a href="{{ route('articles.index') }}" class="block font-medium text-gray-700 hover:text-indigo-600">Artikel</a>
            
            @auth
                <hr class="my-2">
                
                {{-- Messages Link (Mobile) --}}
                @if(Auth::user()->isSeeker() || Auth::user()->isEmployer())
                    @php
                        $unreadMessagesCountMobile = \App\Models\Conversation::active()
                            ->{Auth::user()->isSeeker() ? 'forSeeker' : 'forEmployer'}(Auth::user()->{Auth::user()->isSeeker() ? 'seeker' : 'employer'}->id)
                            ->unread()
                            ->count();
                    @endphp
                    <a href="{{ route('messages.index') }}" class="flex justify-between items-center font-medium text-gray-700 hover:text-indigo-600">
                        <span>Pesan</span>
                        @if($unreadMessagesCountMobile > 0)
                            <span class="px-2 py-1 text-xs font-bold text-white bg-red-500 rounded-full">
                                {{ $unreadMessagesCountMobile }}
                            </span>
                        @endif
                    </a>
                @endif
                <a href="{{ route('profile.show') }}" class="block font-medium text-gray-700 hover:text-indigo-600">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full font-medium text-left text-gray-700 hover:text-indigo-600">
                        Keluar
                    </button>
                </form>
            @else
                <hr class="my-2">
                <a href="{{ route('login') }}" class="block font-medium text-gray-700 hover:text-indigo-600">Masuk / Daftar</a>
                <a href="{{ route('register') }}" class="block px-6 py-2.5 font-medium text-center text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    Pasang Lowongan
                </a>
            @endauth
        </div>
    </div>
</header>

