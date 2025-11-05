<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $seeker->user->name }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Candidate Header -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <div class="flex gap-6 items-start mb-4">
                        <div class="flex-shrink-0">
                            @if($seeker->user->avatar_url)
                                <img 
                                    src="{{ $seeker->user->avatar_url }}" 
                                    alt="{{ $seeker->user->name }}" 
                                    class="object-cover w-24 h-24 rounded-full"
                                >
                            @else
                                <div class="flex justify-center items-center w-24 h-24 bg-indigo-100 rounded-full">
                                    <svg class="w-12 h-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap gap-3 items-center mb-2">
                                <div class="flex items-center gap-2">
                                    <h1 class="text-3xl font-bold text-gray-900">{{ $seeker->user->name }}</h1>
                                    {{-- Favorite Button (for employers only) --}}
                                    @auth
                                        @if(auth()->user()->isEmployer())
                                            <button type="button" 
                                                    id="toggle-favorite-btn"
                                                    class="toggle-favorite-btn flex-shrink-0 p-1 rounded-full transition
                                                        {{ $isFavorite 
                                                            ? 'text-yellow-500 hover:text-yellow-600' 
                                                            : 'text-gray-400 hover:text-yellow-500' }}"
                                                    data-seeker-id="{{ $seeker->id }}"
                                                    data-favorite="{{ $isFavorite ? 'true' : 'false' }}"
                                                    title="{{ $isFavorite ? 'Hapus dari favorit' : 'Tambahkan ke favorit' }}">
                                                <svg class="w-6 h-6" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endauth
                                </div>
                                
                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full">
                                    <svg class="mr-1.5 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                    Job Seeker
                                </span>
                            </div>
                            
                            @if($seeker->current_job_title)
                                <p class="mb-4 text-lg font-medium text-indigo-600">{{ $seeker->current_job_title }}</p>
                            @endif
                            <div class="flex flex-wrap gap-4 text-gray-600">
                                @if($seeker->city || $seeker->country)
                                    <span>📍 {{ $seeker->city }}{{ $seeker->city && $seeker->country ? ', ' : '' }}{{ $seeker->country }}</span>
                                @endif
                                @if($seeker->job_type_preference)
                                    <span>💼 {{ $seeker->job_type_preference }}</span>
                                @endif
                                @if($seeker->user->email)
                                    <span>📧 {{ $seeker->user->email }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 mt-4">
                        @if($seeker->user->resume_slug)
                            <a href="{{ route('resume.show', $seeker->user->resume_slug) }}" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Lihat Resume Publik
                            </a>
                        @endif
                        @if($seeker->portfolio_url)
                            <a href="{{ $seeker->portfolio_url }}" target="_blank" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                Portfolio
                            </a>
                        @endif
                        @if($seeker->linkedin_url)
                            <a href="{{ $seeker->linkedin_url }}" target="_blank" class="px-4 py-2 text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200">
                                LinkedIn
                            </a>
                        @endif
                        @if($seeker->github_url)
                            <a href="{{ $seeker->github_url }}" target="_blank" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                GitHub
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Bio -->
                @if($seeker->bio)
                    <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                        <h2 class="mb-4 text-xl font-bold text-gray-900">Tentang Saya</h2>
                        <p class="text-gray-700 whitespace-pre-line">{{ $seeker->bio }}</p>
                    </div>
                @endif

                <!-- Skills -->
                @if($seeker->skills && is_array($seeker->skills) && count($seeker->skills) > 0)
                    <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                        <h2 class="mb-4 text-xl font-bold text-gray-900">Keterampilan</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($seeker->skills as $skill)
                                <span class="px-3 py-1 text-sm text-indigo-800 bg-indigo-100 rounded-lg">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Experience -->
                @if($seeker->experience && is_array($seeker->experience) && count($seeker->experience) > 0)
                    <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                        <h2 class="mb-4 text-xl font-bold text-gray-900">Pengalaman Kerja</h2>
                        <div class="space-y-6">
                            @foreach($seeker->experience as $exp)
                                <div class="relative pb-6 pl-8 border-l-2 border-gray-200 last:pb-0">
                                    <div class="absolute top-0 -left-2 w-4 h-4 bg-indigo-600 rounded-full"></div>
                                    <h3 class="mb-1 text-lg font-semibold text-gray-900">
                                        {{ $exp['position'] ?? 'Position' }}
                                    </h3>
                                    <p class="mb-2 text-sm font-medium text-indigo-600">
                                        {{ $exp['company'] ?? 'Company' }}
                                    </p>
                                    @if(isset($exp['start_date']) || isset($exp['end_date']))
                                        <p class="mb-2 text-sm text-gray-500">
                                            {{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? 'Sekarang' }}
                                        </p>
                                    @endif
                                    @if(isset($exp['description']))
                                        <p class="text-gray-700">{{ $exp['description'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Education -->
                @if($seeker->education && is_array($seeker->education) && count($seeker->education) > 0)
                    <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                        <h2 class="mb-4 text-xl font-bold text-gray-900">Pendidikan</h2>
                        <div class="space-y-6">
                            @foreach($seeker->education as $edu)
                                <div class="relative pb-6 pl-8 border-l-2 border-gray-200 last:pb-0">
                                    <div class="absolute top-0 -left-2 w-4 h-4 bg-indigo-600 rounded-full"></div>
                                    <h3 class="mb-1 text-lg font-semibold text-gray-900">
                                        {{ $edu['degree'] ?? 'Degree' }}
                                    </h3>
                                    <p class="mb-2 text-sm font-medium text-indigo-600">
                                        {{ $edu['school'] ?? 'School' }}
                                    </p>
                                    @if(isset($edu['start_year']) || isset($edu['end_year']))
                                        <p class="mb-2 text-sm text-gray-500">
                                            {{ $edu['start_year'] ?? '' }} - {{ $edu['end_year'] ?? 'Sekarang' }}
                                        </p>
                                    @endif
                                    @if(isset($edu['description']))
                                        <p class="text-gray-700">{{ $edu['description'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6 lg:col-span-1">
                <!-- Contact Info -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 font-bold text-gray-900">Informasi Kontak</h3>
                    <div class="space-y-3 text-sm">
                        @if($seeker->user->email)
                            <div>
                                <div class="text-gray-600">Email</div>
                                <a href="mailto:{{ $seeker->user->email }}" class="text-indigo-600 break-all hover:text-indigo-700">
                                    {{ $seeker->user->email }}
                                </a>
                            </div>
                        @endif
                        @if($seeker->phone)
                            <div>
                                <div class="text-gray-600">Telepon</div>
                                <a href="tel:{{ $seeker->phone }}" class="text-indigo-600 hover:text-indigo-700">
                                    {{ $seeker->phone }}
                                </a>
                            </div>
                        @endif
                        @if($seeker->full_address)
                            <div>
                                <div class="text-gray-600">Alamat</div>
                                <div>{{ $seeker->full_address }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Job Preferences -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 font-bold text-gray-900">Preferensi Pekerjaan</h3>
                    <div class="space-y-3 text-sm">
                        @if($seeker->job_type_preference)
                            <div>
                                <div class="text-gray-600">Tipe Pekerjaan</div>
                                <div class="font-semibold">{{ $seeker->job_type_preference }}</div>
                            </div>
                        @endif
                        @if($seeker->preferred_location)
                            <div>
                                <div class="text-gray-600">Lokasi Preferensi</div>
                                <div class="font-semibold">{{ $seeker->preferred_location }}</div>
                            </div>
                        @endif
                        @if($seeker->expected_salary_min && $seeker->expected_salary_max)
                            <div>
                                <div class="text-gray-600">Ekspektasi Gaji</div>
                                <div class="font-semibold">
                                    Rp {{ number_format($seeker->expected_salary_min, 0, ',', '.') }} - 
                                    Rp {{ number_format($seeker->expected_salary_max, 0, ',', '.') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- CV/Resume -->
                @if($seeker->cv_path || $seeker->resume_path)
                    <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                        <h3 class="mb-4 font-bold text-gray-900">Dokumen</h3>
                        <div class="space-y-3">
                            @if($seeker->cv_path)
                                <a 
                                    href="{{ Storage::url($seeker->cv_path) }}" 
                                    target="_blank"
                                    class="flex items-center px-4 py-3 text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100"
                                >
                                    📄 Unduh CV
                                </a>
                            @endif
                            @if($seeker->resume_path)
                                <a 
                                    href="{{ Storage::url($seeker->resume_path) }}" 
                                    target="_blank"
                                    class="flex items-center px-4 py-3 text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100"
                                >
                                    📄 Unduh Resume
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Contact CTA -->
                @auth
                    @if(Auth::user()->isAdmin())
                        <div class="overflow-hidden p-6 text-white bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-sm">
                            <h3 class="mb-2 font-bold">Hubungi Kandidat</h3>
                            <p class="mb-4 text-sm text-purple-100">Kirim pesan ke kandidat ini sebagai admin</p>
                            <form action="{{ route('messages.start') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $seeker->user_id }}">
                                <button type="submit"
                                    class="flex justify-center items-center px-4 py-2 w-full text-purple-600 bg-white rounded-lg hover:bg-purple-50">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Kirim Pesan
                                </button>
                            </form>
                        </div>
                    @elseif(Auth::user()->isEmployer())
                        <div class="overflow-hidden p-6 text-white bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-sm">
                            <h3 class="mb-2 font-bold">Tertarik dengan kandidat ini?</h3>
                            <p class="mb-4 text-sm text-indigo-100">Hubungi kandidat untuk mendiskusikan peluang kerja</p>
                            <form action="{{ route('messages.start') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $seeker->user_id }}">
                                <button type="submit"
                                    class="flex justify-center items-center px-4 py-2 w-full text-indigo-600 bg-white rounded-lg hover:bg-indigo-50">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Kirim Pesan
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

@push('scripts')
@auth
    @if(auth()->user()->isEmployer())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-favorite-btn');
            if (!toggleBtn) return;
            
            const starIcon = toggleBtn.querySelector('svg');
            
            toggleBtn.addEventListener('click', function() {
                const seekerId = this.dataset.seekerId;
                const isFavorite = this.dataset.favorite === 'true';
                
                // Disable button during request
                this.disabled = true;
                
                fetch(`/employer/seekers/${seekerId}/toggle-favorite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button state
                        const newFavoriteState = data.saved;
                        this.dataset.favorite = newFavoriteState.toString();
                        
                        if (newFavoriteState) {
                            // Now favorite
                            this.className = 'toggle-favorite-btn flex-shrink-0 p-1 rounded-full transition text-yellow-500 hover:text-yellow-600';
                            starIcon.setAttribute('fill', 'currentColor');
                            this.title = 'Hapus dari favorit';
                        } else {
                            // Not favorite
                            this.className = 'toggle-favorite-btn flex-shrink-0 p-1 rounded-full transition text-gray-400 hover:text-yellow-500';
                            starIcon.setAttribute('fill', 'none');
                            this.title = 'Tambahkan ke favorit';
                        }
                        
                        // Show toast notification
                        if (typeof window.showToast === 'function') {
                            window.showToast(data.message, 'success');
                        } else {
                            console.error('showToast function not found');
                            alert(data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof window.showToast === 'function') {
                        window.showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                    } else {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    </script>
    @endif
@endauth
@endpush
</x-layouts.app>
