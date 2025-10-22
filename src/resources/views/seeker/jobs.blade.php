<x-layouts.dashboard>
    <x-slot name="title">Cari Lowongan</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.seeker />
    </x-slot>

    <x-slot name="header">
        Cari Lowongan
    </x-slot>

    {{-- Filters --}}
    <div class="overflow-hidden p-6 mb-6 bg-white shadow-sm sm:rounded-lg">
        <form action="{{ route('seeker.jobs.index') }}" method="GET" class="space-y-4">
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
                @if(request()->hasAny(['keyword', 'location', 'category', 'job_type']))
                    <a href="{{ route('seeker.jobs.index') }}" class="px-6 py-2 ml-3 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Job Listings Grid --}}
    <div class="grid grid-cols-1 gap-6 px-4 md:grid-cols-2 lg:grid-cols-3 md:px-0">
        @forelse($jobs as $job)
            <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm transition-all duration-300 hover:shadow-lg group"
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
                 }">
                {{-- Card Header --}}
                <div class="p-6 pb-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 transition-colors group-hover:text-indigo-600">
                                <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-indigo-600">
                                    {{ $job->title }}
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                <a href="{{ route('employers.show', $job->employer) }}" class="hover:text-indigo-600 hover:underline">
                                    {{ $job->employer->company_name }}
                                </a>
                            </p>
                        </div>
                        
                        <div class="flex items-center ml-3 space-x-2">
                            @if($job->is_featured)
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                    ⭐ Unggulan
                                </span>
                            @endif
                            
                            {{-- Save Button --}}
                            <button @click="toggleSave()" 
                                    class="p-1 rounded-full transition hover:bg-gray-100"
                                    :title="saved ? 'Hapus dari favorit' : 'Simpan lowongan ini'">
                                <svg class="w-5 h-5 transition-colors" 
                                     :class="saved ? 'text-red-500 fill-current' : 'text-gray-400'"
                                     :fill="saved ? 'currentColor' : 'none'"
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    {{-- Job Info Tags --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md">
                            <svg class="mr-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $job->city }}
                        </span>
                        
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md">
                            <svg class="mr-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                        </span>
                        
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md">
                            <svg class="mr-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ ucfirst(str_replace('-', ' ', $job->work_mode)) }}
                        </span>
                        
                        @if($job->category)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 bg-indigo-100 rounded-md">
                                <svg class="mr-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $job->category->name }}
                            </span>
                        @endif
                    </div>
                    
                    {{-- Salary --}}
                    @if($job->salary_range)
                        <div class="flex items-center mb-4">
                            <svg class="mr-2 w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <span class="text-sm font-semibold text-green-600">{{ $job->salary_range }}</span>
                        </div>
                    @endif
                </div>
                
                {{-- Card Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $job->published_at->diffForHumans() }}
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('jobs.show', $job->slug) }}" 
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg transition-colors hover:bg-indigo-700">
                                Lihat Detail
                            </a>
                            
                            <form action="{{ route('messages.start') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="job_id" value="{{ $job->id }}">
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 bg-white rounded-lg border border-indigo-600 transition-colors hover:bg-indigo-50">
                                    Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="p-12 text-center bg-white rounded-xl border border-gray-200 shadow-sm">
                    <svg class="mx-auto w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada lowongan ditemukan</h3>
                    <p class="mt-2 text-gray-600">Tidak ada lowongan yang sesuai dengan kriteria pencarian Anda.</p>
                    <div class="mt-6">
                        <a href="{{ route('seeker.jobs.index') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg transition-colors hover:bg-indigo-100">
                            Lihat Semua Lowongan
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($jobs->hasPages())
    <div class="mt-8">
        {{ $jobs->links('vendor.pagination.tailwind') }}
    </div>
    @endif

</x-layouts.dashboard>

