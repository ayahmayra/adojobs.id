<x-layouts.dashboard>
    <x-slot name="title">Employer Dashboard</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.employer />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-600">Selamat datang kembali, {{ auth()->user()->name }}!</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('profile.show') }}" 
                   class="inline-flex items-center px-4 py-2 text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Saya
                </a>
                <a href="{{ route('employers.show', $employer) }}" 
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Profil Publik
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Company Profile Summary Card --}}
    <div class="overflow-hidden mb-6 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-sm">
        <div class="p-6 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    @if($employer->company_logo)
                        <img src="{{ asset('storage/' . $employer->company_logo) }}" 
                             alt="{{ $employer->company_name }}"
                             class="object-contain p-2 w-16 h-16 bg-white rounded-lg">
                    @else
                        <div class="flex justify-center items-center w-16 h-16 bg-white rounded-lg">
                            <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                    <div class="ml-4">
                        <h2 class="text-xl font-bold">{{ $employer->company_name }}</h2>
                        <p class="mt-1 text-sm text-indigo-100">
                            {{ $employer->industry ?? 'Industri tidak ditentukan' }}
                            @if($employer->city)
                                • {{ $employer->city }}
                            @endif
                        </p>
                        @if($employer->is_verified)
                            <span class="inline-flex items-center px-2 py-0.5 mt-2 text-xs font-medium bg-white bg-opacity-20 rounded">
                                <svg class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Perusahaan Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('employer.jobs.create') }}" 
                       class="inline-flex items-center px-6 py-3 font-medium text-indigo-600 bg-white rounded-lg transition hover:bg-indigo-50">
                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Pasang Lowongan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Grid --}}
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
        {{-- Total Jobs --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Lowongan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_jobs']) }}</p>
                </div>
            </div>
        </div>

        {{-- Active Jobs --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Lowongan Aktif</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['active_jobs']) }}</p>
                </div>
            </div>
        </div>

        {{-- Total Applications --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Lamaran</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_applications']) }}</p>
                </div>
            </div>
        </div>

        {{-- Unread Messages --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pesan Belum Dibaca</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($unreadMessages) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Application Status Breakdown --}}
    <div class="p-6 mb-6 bg-white rounded-lg shadow-sm">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Ringkasan Status Lamaran</h3>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="p-4 text-center bg-yellow-50 rounded-lg">
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_applications']) }}</p>
                <p class="mt-1 text-sm text-gray-600">Menunggu</p>
            </div>
            <div class="p-4 text-center bg-blue-50 rounded-lg">
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['reviewed_applications']) }}</p>
                <p class="mt-1 text-sm text-gray-600">Ditinjau</p>
            </div>
            <div class="p-4 text-center bg-purple-50 rounded-lg">
                <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['shortlisted_applications']) }}</p>
                <p class="mt-1 text-sm text-gray-600">Diseleksi</p>
            </div>
            <div class="p-4 text-center bg-green-50 rounded-lg">
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['accepted_applications']) }}</p>
                <p class="mt-1 text-sm text-gray-600">Diterima</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions & Content Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Recent Jobs (spans 2 columns) --}}
        <div class="overflow-hidden bg-white rounded-lg shadow-sm lg:col-span-2">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lowongan Terbaru</h3>
                <a href="{{ route('employer.jobs.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    Lihat Semua →
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentJobs as $job)
                    <a href="{{ route('employer.jobs.show', $job) }}" class="block p-4 transition hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $job->title }}</h4>
                                <p class="mt-1 text-sm text-gray-600">{{ $job->category->name ?? 'Uncategorized' }}</p>
                                <div class="flex gap-4 items-center mt-2 text-xs text-gray-500">
                                    <span class="inline-flex items-center">
                                        <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $job->applications_count }} lamaran
                                    </span>
                                    <span>{{ $job->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full font-semibold
                                {{ $job->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $job->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $job->status === 'closed' ? 'bg-red-100 text-red-800' : '' }}">
                                @if($job->status === 'published')
                                    Aktif
                                @elseif($job->status === 'draft')
                                    Draft
                                @elseif($job->status === 'closed')
                                    Ditutup
                                @else
                                    {{ ucfirst($job->status) }}
                                @endif
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-600">
                        <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-2">Belum ada lowongan yang diposting.</p>
                        <a href="{{ route('employer.jobs.create') }}" class="inline-block mt-2 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Pasang lowongan pertama →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            {{-- Contact Admin Card --}}
            <div class="overflow-hidden bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg">
                <div class="p-6 text-white">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 p-2 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="ml-3 font-bold">Hubungi Admin</h3>
                    </div>
                    <p class="mb-4 text-sm text-purple-100">
                        Butuh bantuan atau memiliki pertanyaan? Hubungi admin kami.
                    </p>
                    <form action="{{ route('messages.start') }}" method="POST">
                        @csrf
                        <input type="hidden" name="contact_admin" value="1">
                        <button type="submit"
                                class="flex justify-center items-center px-4 py-2 w-full font-medium text-purple-600 bg-white rounded-lg transition hover:bg-purple-50">
                            <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Kirim Pesan ke Admin
                        </button>
                    </form>
                </div>
            </div>

            {{-- Recent Messages --}}
            <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pesan Terbaru</h3>
                    <a href="{{ route('messages.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Lihat Semua →
                    </a>
                </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentConversations as $conversation)
                    <a href="{{ route('messages.show', $conversation) }}" class="block p-4 transition hover:bg-gray-50">
                        <div class="flex gap-3 items-start">
                            <img src="{{ $conversation->other_participant_avatar }}" 
                                 alt="{{ $conversation->other_participant }}"
                                 class="object-cover w-10 h-10 rounded-full">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $conversation->other_participant }}
                                </p>
                                @if($conversation->subject)
                                    <p class="text-xs text-gray-500 truncate">{{ $conversation->subject }}</p>
                                @endif
                                @if($conversation->lastMessage)
                                    <p class="mt-1 text-xs text-gray-500 truncate">
                                        {{ Str::limit($conversation->lastMessage->message, 50) }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $conversation->lastMessage->created_at->diffForHumans() }}
                                    </p>
                                @endif
                                @if($conversation->unread_count > 0)
                                    <span class="inline-block px-2 py-0.5 mt-1 text-xs font-bold text-white bg-red-500 rounded-full">
                                        {{ $conversation->unread_count }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-600">
                        <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="mt-2 text-sm">Belum ada pesan.</p>
                    </div>
                @endforelse
            </div>
            </div>
        </div>
    </div>

    {{-- Recent Applications --}}
    <div class="overflow-hidden mt-6 bg-white rounded-lg shadow-sm">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Lamaran Terbaru</h3>
            <a href="{{ route('employer.applications.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Lihat Semua →
            </a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recentApplications as $application)
                <a href="{{ route('employer.applications.show', $application) }}" class="block p-4 transition hover:bg-gray-50">
                    <div class="flex gap-4 items-center">
                        <img src="{{ $application->seeker->user->avatar_url }}" 
                             alt="{{ $application->seeker->user->name }}" 
                             class="object-cover w-12 h-12 rounded-full">
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $application->seeker->user->name }}</p>
                                    <p class="text-sm text-gray-600">Melamar: {{ $application->job->title }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 text-xs rounded-full font-semibold
                                        {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $application->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $application->status === 'shortlisted' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        @if($application->status === 'pending')
                                            Menunggu
                                        @elseif($application->status === 'reviewed')
                                            Ditinjau
                                        @elseif($application->status === 'shortlisted')
                                            Diseleksi
                                        @elseif($application->status === 'accepted')
                                            Diterima
                                        @elseif($application->status === 'rejected')
                                            Ditolak
                                        @else
                                            {{ ucfirst($application->status) }}
                                        @endif
                                    </span>
                                    <p class="mt-1 text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-600">
                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-2">Belum ada lamaran masuk.</p>
                </div>
            @endforelse
        </div>
    </div>

</x-layouts.dashboard>
