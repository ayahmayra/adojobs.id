<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Cari Lowongan Kerja') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        

        <!-- Filters -->
        <div class="overflow-hidden p-6 mb-6 bg-white shadow-sm sm:rounded-lg">
            <form action="{{ route('jobs.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                               placeholder="Cari lowongan..." 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <input type="text" name="location" value="{{ request('location') }}"
                               placeholder="Lokasi" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <select name="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="job_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Tipe</option>
                            <option value="full-time" {{ request('job_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ request('job_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                            <option value="freelance" {{ request('job_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                            <option value="internship" {{ request('job_type') == 'internship' ? 'selected' : '' }}>Magang</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Job Listings -->
        <div class="space-y-4">
            @forelse($jobs as $job)
            <div class="overflow-hidden p-6 bg-white shadow-sm transition sm:rounded-lg hover:shadow-md"
                 @auth
                     @if(auth()->user()->isSeeker())
                         x-data="{ 
                            saved: {{ auth()->user()->seeker && auth()->user()->seeker->hasSavedJob($job->id) ? 'true' : 'false' }},
                            toggleSave() {
                                fetch('{{ route('seeker.jobs.toggle-save', $job) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        this.saved = data.saved;
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                            }
                         }"
                     @endif
                 @endauth>
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex gap-2 items-center mb-2">
                            <h2 class="text-xl font-semibold text-gray-900">
                                <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-indigo-600">
                                    {{ $job->title }}
                                </a>
                            </h2>
                            @if($job->is_featured)
                            <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded">Unggulan</span>
                            @endif
                            
                            {{-- Save Button --}}
                            @auth
                                @if(auth()->user()->isSeeker())
                                    <button @click="toggleSave()" 
                                            class="p-1 ml-2 rounded-full transition hover:bg-gray-100"
                                            :title="saved ? 'Hapus dari favorit' : 'Simpan lowongan ini'">
                                        <svg class="w-6 h-6 transition-colors" 
                                             :class="saved ? 'text-red-500 fill-current' : 'text-gray-400'"
                                             :fill="saved ? 'currentColor' : 'none'"
                                             stroke="currentColor" 
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                @endif
                            @else
                                {{-- Guest users: Show login prompt --}}
                                <a href="{{ route('login') }}" 
                                   class="p-1 ml-2 rounded-full transition hover:bg-gray-100"
                                   title="Login untuk menyimpan lowongan ini">
                                    <svg class="w-6 h-6 text-gray-400" 
                                         fill="none"
                                         stroke="currentColor" 
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </a>
                            @endauth
                        </div>
                        <p class="mb-3 text-gray-600">
                            <a href="{{ route('employers.show', $job->employer) }}" class="hover:text-indigo-600 hover:underline">
                                {{ $job->employer->company_name }}
                            </a>
                        </p>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                            <span>📍 {{ $job->city }}</span>
                            <span>💼 {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</span>
                            <span>🏢 {{ ucfirst(str_replace('-', ' ', $job->work_mode)) }}</span>
                            @if($job->category)
                            <span>📁 {{ $job->category->name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4 text-right">
                        @if($job->salary_range)
                        <div class="mb-2 font-semibold text-gray-900">{{ $job->salary_range }}</div>
                        @endif
                        <div class="text-xs text-gray-500">Diposting {{ $job->published_at->diffForHumans() }}</div>
                        <div class="flex gap-2 mt-3">
                            <a href="{{ route('jobs.show', $job->slug) }}" 
                               class="inline-block px-4 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                Lihat Detail
                            </a>
                            @auth
                                @if(auth()->user()->isSeeker())
                                    <form action="{{ route('messages.start') }}" method="POST" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        <button type="submit"
                                                class="px-4 py-2 text-sm text-indigo-600 bg-white rounded-md border border-indigo-600 hover:bg-indigo-50">
                                            Kirim Pesan
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="overflow-hidden p-12 text-center bg-white shadow-sm sm:rounded-lg">
                <p class="text-gray-600">Tidak ada lowongan yang sesuai dengan kriteria pencarian Anda.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
</x-layouts.app>
