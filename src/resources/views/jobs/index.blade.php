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

        <!-- Job Listings Grid -->
        <div class="grid grid-cols-1 gap-6 px-4 md:grid-cols-2 lg:grid-cols-3 md:px-0">
            @forelse($jobs as $job)
                <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm transition-all duration-300 hover:shadow-lg group"
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
                    {{-- Card Header --}}
                    <div class="p-6 pb-4">
                        {{-- Title & Featured Badge --}}
                        <div class="flex justify-between items-start mb-3">
                            <h2 class="text-lg font-semibold text-gray-900 transition-colors line-clamp-2 group-hover:text-indigo-600">
                                <a href="{{ route('jobs.show', $job->slug) }}">
                                    {{ $job->title }}
                                </a>
                            </h2>
                            <div class="flex gap-2 items-center ml-2">
                                @if($job->is_featured)
                                <span class="px-2 py-1 text-xs font-medium text-yellow-800 whitespace-nowrap bg-yellow-100 rounded-full">
                                    ⭐ Unggulan
                                </span>
                                @endif
                                
                                {{-- Save Button --}}
                                @auth
                                    @if(auth()->user()->isSeeker())
                                        <button @click="toggleSave()" 
                                                class="p-1.5 rounded-full transition hover:bg-gray-100"
                                                :title="saved ? 'Hapus dari favorit' : 'Simpan lowongan ini'">
                                            <svg class="w-5 h-5 transition-colors" 
                                                 :class="saved ? 'text-red-500 fill-current' : 'text-gray-400'"
                                                 :fill="saved ? 'currentColor' : 'none'"
                                                 stroke="currentColor" 
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="p-1.5 rounded-full transition hover:bg-gray-100"
                                       title="Login untuk menyimpan lowongan ini">
                                        <svg class="w-5 h-5 text-gray-400" 
                                             fill="none"
                                             stroke="currentColor" 
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </a>
                                @endauth
                            </div>
                        </div>

                        {{-- Company Name --}}
                        <p class="mb-4 text-sm text-gray-600">
                            <a href="{{ route('employers.show', $job->employer) }}" class="hover:text-indigo-600 hover:underline">
                                {{ $job->employer->company_name }}
                            </a>
                        </p>

                        {{-- Job Info Tags --}}
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="inline-flex gap-1 items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $job->city }}
                            </span>
                            <span class="inline-flex gap-1 items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                            </span>
                            <span class="inline-flex gap-1 items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ ucfirst(str_replace('-', ' ', $job->work_mode)) }}
                            </span>
                            @if($job->category)
                            <span class="inline-flex gap-1 items-center px-2 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-md">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $job->category->name }}
                            </span>
                            @endif
                        </div>

                        {{-- Salary --}}
                        @if($job->salary_range)
                        <div class="flex gap-2 items-center mb-4">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-lg font-bold text-green-700">{{ $job->salary_range }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Card Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-3">
                            <div class="flex gap-1 items-center text-xs text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $job->published_at->diffForHumans() }}
                            </div>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <a href="{{ route('jobs.show', $job->slug) }}" 
                               class="flex-1 px-4 py-2 text-sm font-medium text-center text-white bg-indigo-600 rounded-lg transition-colors hover:bg-indigo-700">
                                Lihat Detail
                            </a>
                            @auth
                                @if(auth()->user()->isSeeker())
                                    <form action="{{ route('messages.start') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        <button type="submit"
                                                class="px-4 py-2 w-full text-sm font-medium text-indigo-600 bg-white rounded-lg border border-indigo-600 transition-colors hover:bg-indigo-50">
                                            Kirim Pesan
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada lowongan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba ubah filter pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="px-4 mt-6 md:px-0">
            <div class="flex justify-center">
                {{ $jobs->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
