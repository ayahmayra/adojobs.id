<x-layouts.app>
    <x-slot name="title">{{ $category->name }} - Lowongan Kerja</x-slot>

    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    @if($category->icon)
                        <div class="flex justify-center items-center w-16 h-16 text-3xl bg-white rounded-lg">
                            <span class="emoji-icon" data-icon="{{ $category->icon }}">{{ $category->icon }}</span>
                        </div>
                    @else
                        <div class="flex justify-center items-center w-16 h-16 bg-white rounded-lg">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="ml-6">
                    <h1 class="text-4xl font-bold text-white">{{ $category->name }}</h1>
                    <p class="mt-2 text-xl text-indigo-100">
                        {{ $jobs->total() }} lowongan tersedia
                    </p>
                    @if($category->description)
                        <p class="mt-3 text-lg text-indigo-100">
                            {{ $category->description }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200">
        <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <a href="{{ route('categories.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Kategori
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">{{ $category->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
            {{-- Main Content --}}
            <div class="lg:col-span-3">
                {{-- Jobs List --}}
                @if($jobs->count() > 0)
                    <!-- Job Listings Grid -->
                    <div class="grid grid-cols-1 gap-6 px-4 md:grid-cols-2 lg:grid-cols-2 md:px-0">
                        @foreach($jobs as $job)
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
                                        <span class="inline-flex gap-1 items-center px-2 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-md">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $category->name }}
                                        </span>
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
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="px-4 mt-6 md:px-0">
                        <div class="flex justify-center">
                            {{ $jobs->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="col-span-full py-12 text-center">
                        <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Lowongan</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Belum ada lowongan dalam kategori {{ $category->name }}.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('jobs.index') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg transition hover:bg-indigo-100">
                                Lihat Semua Lowongan
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                {{-- Related Categories --}}
                @if($relatedCategories->count() > 0)
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Kategori Lainnya</h3>
                        <div class="space-y-3">
                            @foreach($relatedCategories as $relatedCategory)
                                <a href="{{ route('categories.show', $relatedCategory) }}" 
                                   class="flex items-center p-3 text-gray-700 bg-gray-50 rounded-lg transition hover:bg-indigo-50 hover:text-indigo-600">
                                    @if($relatedCategory->icon)
                                        <div class="flex justify-center items-center mr-3 w-8 h-8 text-lg bg-white rounded-lg">
                                            <span class="emoji-icon" data-icon="{{ $relatedCategory->icon }}">{{ $relatedCategory->icon }}</span>
                                        </div>
                                    @else
                                        <div class="flex justify-center items-center mr-3 w-8 h-8 bg-gray-200 rounded-lg">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="font-medium">{{ $relatedCategory->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $relatedCategory->jobs_count }} lowongan</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- CTA Section --}}
                <div class="p-6 mt-6 text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
                    <h3 class="mb-2 text-lg font-semibold">Cari Lowongan Lainnya?</h3>
                    <p class="mb-4 text-sm text-indigo-100">
                        Jelajahi semua kategori dan temukan lowongan yang sesuai
                    </p>
                    <a href="{{ route('categories.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-white rounded-lg transition hover:bg-indigo-50">
                        Lihat Semua Kategori
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Emoji Icon Fix --}}
    <style>
        .emoji-icon {
            font-family: 'Apple Color Emoji', 'Segoe UI Emoji', 'Noto Color Emoji', 'Twemoji Mozilla', sans-serif;
            font-size: 1.5rem;
            line-height: 1;
            display: inline-block;
            text-align: center;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iconMappings = {
                'computer': '💻', 'megaphone': '📢', 'palette': '🎨', 'calculator': '🧮', 'users': '👥',
                'cog': '⚙️', 'heart': '❤️', 'briefcase': '💼', 'chart': '📊', 'building': '🏢',
                'mobile': '📱', 'wrench': '🔧', 'globe': '🌐', 'paint': '🎨', 'money': '💰',
                'car': '🚗', 'home': '🏠', 'hospital': '🏥', 'book': '📚', 'music': '🎵',
                'camera': '📷', 'star': '⭐', 'lightbulb': '💡', 'target': '🎯', 'shield': '🛡️'
            };
            document.querySelectorAll('.emoji-icon').forEach(function(element) {
                const currentIcon = element.getAttribute('data-icon');
                const emojiIcon = iconMappings[currentIcon];
                if (emojiIcon && currentIcon !== emojiIcon) {
                    element.textContent = emojiIcon;
                    element.setAttribute('data-icon', emojiIcon);
                }
            });
        });
    </script>
</x-layouts.app>
