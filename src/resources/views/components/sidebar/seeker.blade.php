{{-- Seeker Sidebar Component --}}

{{-- Dashboard --}}
<a href="{{ route('seeker.dashboard') }}" 
   class="{{ request()->routeIs('seeker.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    Dashboard
</a>

{{-- Browse Jobs --}}
<a href="{{ route('seeker.jobs.index') }}" 
   class="{{ request()->routeIs('seeker.jobs.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
    </svg>
    Cari Lowongan
</a>

{{-- My Applications --}}
<a href="{{ route('seeker.applications.index') }}" 
   class="{{ request()->routeIs('seeker.applications.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    Lamaran Saya
</a>

{{-- Saved Jobs --}}
<a href="{{ route('seeker.saved-jobs.index') }}" 
   class="{{ request()->routeIs('seeker.saved-jobs.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
    Lowongan Favorit
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
            ->forSeeker(Auth::user()->seeker->id)
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

{{-- My Profile --}}
<a href="{{ route('profile.show') }}" 
   class="{{ request()->routeIs('profile.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} flex items-center px-4 py-3 rounded-lg font-medium transition">
    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    Profil Saya
</a>


