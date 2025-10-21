{{-- Employer Sidebar Component --}}

{{-- Dashboard --}}
<a href="{{ route('employer.dashboard') }}" 
   class="{{ request()->routeIs('employer.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    Dashboard
</a>

{{-- My Jobs --}}
<a href="{{ route('employer.jobs.index') }}" 
   class="{{ request()->routeIs('employer.jobs.*') && !request()->routeIs('employer.jobs.applications.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
    </svg>
    Lowongan Saya
</a>

{{-- Applications --}}
<a href="{{ route('employer.applications.index') }}" 
   class="{{ request()->routeIs('employer.applications.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    Lamaran Masuk
</a>

{{-- Messages --}}
<a href="{{ route('messages.index') }}" 
   class="{{ request()->routeIs('messages.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} flex items-center px-4 py-3 rounded-lg font-medium transition relative">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
    </svg>
    Pesan
    @php
        $unreadCount = \App\Models\Conversation::active()
            ->forEmployer(Auth::user()->employer->id)
            ->unread()
            ->count();
    @endphp
    @if($unreadCount > 0)
        <span class="px-2 py-0.5 ml-auto text-xs font-bold text-white bg-red-500 rounded-full">
            {{ $unreadCount }}
        </span>
    @endif
</a>

{{-- Divider --}}
<div class="my-4 border-t border-gray-200"></div>

{{-- Company Profile --}}
<a href="{{ route('profile.show') }}" 
   class="{{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
    </svg>
    Profil Perusahaan
</a>

{{-- Statistics --}}
<a href="#" 
   class="flex items-center px-4 py-3 font-medium text-gray-700 rounded-lg transition hover:bg-gray-50">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
    </svg>
    Statistik
</a>

