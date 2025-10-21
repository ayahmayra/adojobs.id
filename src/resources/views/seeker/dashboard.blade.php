<x-layouts.dashboard>
    <x-slot name="title">Dashboard Pencari Kerja</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.seeker />
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
                @if($seeker->user->hasPublicResume())
                    <a href="{{ route('resume.show', $seeker->user->resume_slug) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 text-gray-700 bg-white rounded-lg border border-gray-300 transition hover:bg-gray-50">
                        <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Resume Publik
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    {{-- Profile Summary Card --}}
    <div class="overflow-hidden mb-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-sm">
        <div class="p-6 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    @if($seeker->user->avatar)
                        <img src="{{ $seeker->user->avatar_url }}" 
                             alt="{{ $seeker->user->name }}"
                             class="object-cover w-16 h-16 rounded-lg">
                    @else
                        <div class="flex justify-center items-center w-16 h-16 bg-white rounded-lg">
                            <span class="text-2xl font-bold text-blue-600">
                                {{ substr($seeker->user->name, 0, 2) }}
                            </span>
                        </div>
                    @endif
                    <div class="ml-4">
                        <h2 class="text-xl font-bold">{{ $seeker->user->name }}</h2>
                        <p class="mt-1 text-sm text-blue-100">
                            {{ $seeker->current_job_title ?? 'Pencari Kerja' }}
                            @if($seeker->city)
                                • {{ $seeker->city }}
                            @endif
                        </p>
                        @if($seeker->user->hasPublicResume())
                            <span class="inline-flex items-center px-2 py-0.5 mt-2 text-xs font-medium bg-white bg-opacity-20 rounded">
                                <svg class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Resume Publik Tersedia
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('jobs.index') }}" 
                       class="inline-flex items-center px-6 py-3 font-medium text-blue-600 bg-white rounded-lg transition hover:bg-blue-50">
                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari Lowongan
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Grid --}}
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
        {{-- Total Applications --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Lamaran</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_applications']) }}</p>
                </div>
            </div>
        </div>

        {{-- Pending Applications --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Menunggu</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_applications']) }}</p>
                </div>
            </div>
        </div>

        {{-- Shortlisted Applications --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Diseleksi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['shortlisted_applications']) }}</p>
                </div>
            </div>
        </div>

        {{-- Accepted Applications --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Diterima</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['accepted_applications']) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Application Status Breakdown --}}
    <div class="p-6 mb-6 bg-white rounded-lg shadow-sm">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Ringkasan Status Lamaran</h3>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_applications'] }}</div>
                <div class="text-sm text-gray-600">Menunggu</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_applications'] - $stats['pending_applications'] - $stats['shortlisted_applications'] - $stats['accepted_applications'] }}</div>
                <div class="text-sm text-gray-600">Ditinjau</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $stats['shortlisted_applications'] }}</div>
                <div class="text-sm text-gray-600">Diseleksi</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['accepted_applications'] }}</div>
                <div class="text-sm text-gray-600">Diterima</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-600">{{ $stats['saved_jobs'] }}</div>
                <div class="text-sm text-gray-600">Tersimpan</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Recent Applications --}}
        <div class="overflow-hidden bg-white rounded-lg shadow-sm lg:col-span-1">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lamaran Terbaru</h3>
                <a href="{{ route('seeker.applications.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    Lihat Semua →
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentApplications as $application)
                    <div class="p-4 transition hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $application->job->title }}</h4>
                                <p class="mt-1 text-sm text-gray-600">
                                    <a href="{{ route('employers.show', $application->job->employer) }}" class="hover:text-indigo-600 hover:underline">
                                        {{ $application->job->employer->company_name }}
                                    </a>
                                </p>
                                <div class="flex gap-4 items-center mt-2 text-xs text-gray-500">
                                    <span>{{ $application->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full font-semibold
                                {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $application->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $application->status === 'shortlisted' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $application->status === 'hired' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                @if($application->status === 'pending')
                                    Menunggu
                                @elseif($application->status === 'reviewed')
                                    Ditinjau
                                @elseif($application->status === 'shortlisted')
                                    Diseleksi
                                @elseif($application->status === 'hired')
                                    Diterima
                                @elseif($application->status === 'rejected')
                                    Ditolak
                                @else
                                    {{ ucfirst($application->status) }}
                                @endif
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-600">
                        <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-2">Belum ada lamaran yang dikirim.</p>
                        <a href="{{ route('jobs.index') }}" class="inline-block mt-2 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Cari lowongan sekarang →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6 lg:col-span-1">
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
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="object-cover w-10 h-10 rounded-full" 
                                     src="{{ $conversation->other_participant_avatar }}" 
                                     alt="{{ $conversation->other_participant }}">
                            </div>
                            <div class="flex-1 ml-3">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $conversation->other_participant }}
                                        </p>
                                        @if($conversation->subject)
                                            <p class="text-xs text-gray-500 truncate">{{ $conversation->subject }}</p>
                                        @endif
                                    </div>
                                    @if($conversation->unread_count > 0)
                                        <span class="inline-flex items-center px-2 py-1 ml-2 text-xs font-medium text-white bg-red-500 rounded-full">
                                            {{ $conversation->unread_count }}
                                        </span>
                                    @endif
                                </div>
                                @if($conversation->lastMessage)
                                    <p class="mt-1 text-sm text-gray-500 truncate">{{ Str::limit($conversation->lastMessage->message, 50) }}</p>
                                    <p class="text-xs text-gray-400">{{ $conversation->lastMessage->created_at->diffForHumans() }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-600">
                        <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="mt-2">Belum ada pesan.</p>
                        <p class="text-sm">Mulai berkomunikasi dengan perusahaan</p>
                    </div>
                @endforelse
            </div>
            </div>
        </div>

    </div>

    {{-- Saved Jobs --}}
    <div class="overflow-hidden mt-6 bg-white rounded-lg shadow-sm">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Lowongan Tersimpan</h3>
            <a href="{{ route('seeker.saved-jobs.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Lihat Semua →
            </a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($savedJobs as $savedJob)
                <a href="{{ route('jobs.show', $savedJob->job->slug) }}" class="block p-4 transition hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $savedJob->job->title }}</h4>
                            <p class="mt-1 text-sm text-gray-600">
                                <a href="{{ route('employers.show', $savedJob->job->employer) }}" class="hover:text-indigo-600 hover:underline">
                                    {{ $savedJob->job->employer->company_name }}
                                </a>
                            </p>
                            <div class="flex gap-4 items-center mt-2 text-xs text-gray-500">
                                <span>{{ $savedJob->job->city }}</span>
                                <span>
                                    @if($savedJob->job->job_type === 'full-time') Penuh Waktu
                                    @elseif($savedJob->job->job_type === 'part-time') Paruh Waktu
                                    @elseif($savedJob->job->job_type === 'contract') Kontrak
                                    @elseif($savedJob->job->job_type === 'freelance') Freelance
                                    @elseif($savedJob->job->job_type === 'internship') Magang
                                    @else {{ ucfirst(str_replace('-', ' ', $savedJob->job->job_type)) }}
                                    @endif
                                </span>
                                <span>{{ $savedJob->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-600">
                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    <p class="mt-2">Belum ada lowongan tersimpan.</p>
                    <p class="text-sm">Simpan lowongan untuk dilihat nanti</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="p-6 mt-6 bg-white rounded-lg shadow-sm">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Aksi Cepat</h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <a href="{{ route('jobs.index') }}" 
               class="flex items-center p-4 text-gray-700 bg-gray-50 rounded-lg transition hover:bg-gray-100">
                <svg class="mr-3 w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <div>
                    <div class="font-medium">Cari Lowongan</div>
                    <div class="text-sm text-gray-500">Temukan pekerjaan yang sesuai</div>
                </div>
            </a>
            
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center p-4 text-gray-700 bg-gray-50 rounded-lg transition hover:bg-gray-100">
                <svg class="mr-3 w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <div>
                    <div class="font-medium">Edit Profil</div>
                    <div class="text-sm text-gray-500">Lengkapi informasi profil</div>
                </div>
            </a>
            
            @if(!$seeker->user->hasPublicResume())
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center p-4 text-gray-700 bg-gray-50 rounded-lg transition hover:bg-gray-100">
                    <svg class="mr-3 w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <div>
                        <div class="font-medium">Buat Resume</div>
                        <div class="text-sm text-gray-500">Buat resume publik</div>
                    </div>
                </a>
            @else
                <a href="{{ route('resume.show', $seeker->user->resume_slug) }}" 
                   target="_blank"
                   class="flex items-center p-4 text-gray-700 bg-gray-50 rounded-lg transition hover:bg-gray-100">
                    <svg class="mr-3 w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <div>
                        <div class="font-medium">Lihat Resume</div>
                        <div class="text-sm text-gray-500">Resume publik Anda</div>
                    </div>
                </a>
            @endif
        </div>
    </div>
</x-layouts.dashboard>