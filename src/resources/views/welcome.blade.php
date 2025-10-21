<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ mobileMenuOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdoJobs.id - Jalan Pintas Menuju Karier dan Talenta Terbaik!</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=jost:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Jost', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    
    {{-- Header --}}
    <x-header />

    {{-- Hero Section --}}
    <section class="overflow-hidden relative pt-20 pb-32 bg-gradient-to-br from-indigo-50 via-white to-blue-50">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-400 rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-400 rounded-full filter blur-3xl"></div>
        </div>

        <div class="relative z-10 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-12 items-center lg:grid-cols-2">
                {{-- Left Content --}}
                <div>
                    <div class="inline-flex items-center px-4 py-2 mb-6 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-full">
                        <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Tepercaya oleh 300+ Perusahaan
                    </div>
                    
                    <h1 class="mb-6 text-5xl font-bold leading-tight text-gray-900 lg:text-6xl">
                        Silahkan cari <span class="text-indigo-600">Pekerjaan</span> yang tepat untuk kamu!
                    </h1>
                    
                    <p class="mb-8 text-xl leading-relaxed text-gray-600">
                        Temukan karier impian Anda atau ciptakan tim impian di AdoJobs.id. Dengan fitur lengkap dan mudah digunakan, kami menjembatani pelamar kerja dengan perusahaan impian mereka.
                    </p>

                    {{-- Search Form --}}
                    <form action="{{ route('jobs.index') }}" method="GET" class="p-2 bg-white rounded-2xl shadow-xl">
                        <div class="grid grid-cols-1 gap-2 md:grid-cols-12">
                            <div class="md:col-span-5">
                                <div class="relative">
                                    <svg class="absolute left-4 top-1/2 w-5 h-5 text-gray-400 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" name="keyword" placeholder="Masukan nama pekerjaan" 
                                           class="py-4 pr-4 pl-12 w-full rounded-xl border-0 focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                            <div class="md:col-span-4">
                                <div class="relative">
                                    <svg class="absolute left-4 top-1/2 w-5 h-5 text-gray-400 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <select name="category" class="py-4 pr-4 pl-12 w-full rounded-xl border-0 appearance-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Kategori</option>
                                        <option>Administrasi</option>
                                        <option>Desain</option>
                                        <option>Development</option>
                                        <option>Marketing</option>
                                    </select>
                                </div>
                            </div>
                            <div class="md:col-span-3">
                                <button type="submit" class="py-4 w-full font-semibold text-white bg-indigo-600 rounded-xl shadow-lg transition hover:bg-indigo-700 shadow-indigo-200">
                                    Cari Pekerjaan
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Popular Keywords --}}
                    <div class="mt-6">
                        <p class="mb-3 text-sm text-gray-600"><span class="font-semibold">Pencarian populer:</span></p>
                        <div class="flex flex-wrap gap-2">
                            <a href="#" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-full transition hover:bg-indigo-100 hover:text-indigo-700">Designer</a>
                            <a href="#" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-full transition hover:bg-indigo-100 hover:text-indigo-700">Developer</a>
                            <a href="#" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-full transition hover:bg-indigo-100 hover:text-indigo-700">Web</a>
                            <a href="#" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-full transition hover:bg-indigo-100 hover:text-indigo-700">IOS</a>
                            <a href="#" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-full transition hover:bg-indigo-100 hover:text-indigo-700">Senior</a>
                        </div>
                    </div>
                </div>

                {{-- Right Image --}}
                <div class="hidden relative lg:block">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600" alt="Professional" class="rounded-3xl shadow-2xl">
                        
                        {{-- Floating Cards --}}
                        <div class="absolute -top-6 -right-6 p-4 bg-white rounded-2xl shadow-xl">
                            <div class="flex items-center space-x-3">
                                <div class="flex justify-center items-center w-12 h-12 bg-green-100 rounded-full">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Job Candidates</p>
                                    <p class="text-2xl font-bold text-gray-900">2,150</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-6 -left-6 p-4 bg-white rounded-2xl shadow-xl">
                            <div class="flex items-center space-x-3">
                                <div class="flex justify-center items-center w-12 h-12 bg-indigo-100 rounded-full">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Creative Agency</p>
                                    <p class="text-2xl font-bold text-gray-900">350+</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Popular Categories --}}
    <section class="py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="mb-4 text-4xl font-bold text-gray-900">Kategori Pekerjaan Populer</h2>
                <p class="text-xl text-gray-600">Temukan pekerjaan berdasarkan kategori yang Anda minati</p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @php
                $categories = \App\Models\Category::active()
                    ->withCount('jobs')
                    ->inRandomOrder()
                    ->take(6)
                    ->get();
                @endphp

                @forelse($categories as $category)
                <a href="{{ route('categories.show', $category) }}" 
                   class="block p-6 bg-white rounded-xl shadow-sm transition group hover:shadow-lg hover:-translate-y-1">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if($category->icon)
                                <div class="flex justify-center items-center w-16 h-16 text-3xl bg-indigo-100 rounded-xl transition group-hover:scale-110">
                                    <span class="emoji-icon" data-icon="{{ $category->icon }}">{{ $category->icon }}</span>
                                </div>
                            @else
                                <div class="flex justify-center items-center w-16 h-16 bg-gray-100 rounded-xl">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 ml-5">
                            <h3 class="mb-2 text-xl font-semibold text-gray-900 transition group-hover:text-indigo-600">
                                {{ $category->name }}
                            </h3>
                            <p class="text-sm font-medium text-gray-600">
                                {{ $category->jobs_count }} lowongan tersedia
                            </p>
                            @if($category->description)
                                <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                                    {{ $category->description }}
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full">
                    <div class="p-12 text-center bg-white rounded-xl shadow-sm">
                        <svg class="mx-auto w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Belum Ada Kategori</h3>
                        <p class="mt-2 text-gray-600">Kategori lowongan akan muncul di sini</p>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-8 py-3 font-semibold text-indigo-600 rounded-xl border-2 border-indigo-600 transition hover:bg-indigo-600 hover:text-white">
                    Lihat Semua Kategori
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
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
    </section>

    {{-- Featured Jobs --}}
    <section class="py-20 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="mb-4 text-4xl font-bold text-gray-900">Pekerjaan Pilihan</h2>
                <p class="text-xl text-gray-600">Ketahui nilai dirimu dan temukan pekerjaan yang sesuai denganmu</p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @php
                $featuredJobs = \App\Models\Job::where('status', 'published')
                    ->where('is_featured', true)
                    ->where(function($query) {
                        $query->whereNull('application_deadline')
                              ->orWhere('application_deadline', '>=', now());
                    })
                    ->with(['employer.user', 'category'])
                    ->latest()
                    ->take(6)
                    ->get();
                @endphp

                @forelse($featuredJobs as $job)
                <div class="p-6 bg-white rounded-2xl transition hover:shadow-xl group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex flex-1 items-start space-x-4">
                            @if($job->employer->company_logo)
                                <img src="{{ asset('storage/' . $job->employer->company_logo) }}" 
                                     alt="{{ $job->employer->company_name }}" 
                                     class="object-cover flex-shrink-0 w-16 h-16 rounded-xl">
                            @elseif($job->employer->user->avatar)
                                <img src="{{ $job->employer->user->avatar_url }}" 
                                     alt="{{ $job->employer->company_name }}" 
                                     class="object-cover flex-shrink-0 w-16 h-16 rounded-xl">
                            @else
                                <div class="flex flex-shrink-0 justify-center items-center w-16 h-16 text-xl font-bold text-white bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl">
                                    {{ substr($job->employer->company_name, 0, 2) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="mb-1 text-xl font-semibold text-gray-900 group-hover:text-indigo-600">
                                    <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
                                </h3>
                                <p class="text-gray-600">
                                    <a href="{{ route('employers.show', $job->employer) }}" class="hover:text-indigo-600">
                                        {{ $job->employer->company_name }}
                                    </a>
                                </p>
                            </div>
                        </div>
                        @auth
                            @if(auth()->user()->isSeeker())
                                <button class="text-gray-400 transition hover:text-red-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            @endif
                        @endauth
                    </div>

                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                            {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                        </span>
                        @if($job->is_featured)
                            <span class="px-3 py-1 text-sm font-medium text-yellow-700 bg-yellow-100 rounded-full">⭐ Featured</span>
                        @endif
                        <span class="px-3 py-1 text-sm font-medium text-blue-700 bg-blue-100 rounded-full">
                            {{ ucfirst($job->work_mode) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-4">
                            <span class="flex items-center">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $job->city }}
                            </span>
                            <span class="flex items-center">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $job->created_at->diffForHumans() }}
                            </span>
                        </div>
                        @if($job->salary_min || $job->salary_max)
                            <span class="font-semibold text-indigo-600">
                                @if($job->salary_min && $job->salary_max)
                                    Rp {{ number_format($job->salary_min / 1000000, 0) }}-{{ number_format($job->salary_max / 1000000, 0) }} Jt
                                @elseif($job->salary_min)
                                    Rp {{ number_format($job->salary_min / 1000000, 0) }} Jt+
                                @elseif($job->salary_max)
                                    Up to Rp {{ number_format($job->salary_max / 1000000, 0) }} Jt
                                @endif
                            </span>
                        @endif
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t">
                        <div class="flex items-center space-x-2 text-sm text-gray-600">
                            @if($job->category)
                                <span class="px-2 py-1 text-indigo-600 bg-indigo-50 rounded">{{ $job->category->name }}</span>
                            @endif
                        </div>
                        <a href="{{ route('jobs.show', $job->slug) }}" class="font-medium text-indigo-600 hover:text-indigo-700">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="p-12 text-center bg-white rounded-2xl shadow-sm">
                        <svg class="mx-auto w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Belum Ada Lowongan Featured</h3>
                        <p class="mt-2 text-gray-600">Lowongan featured akan muncul di sini</p>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-8 py-4 font-semibold text-white bg-indigo-600 rounded-xl shadow-lg transition hover:bg-indigo-700 shadow-indigo-200">
                    Lihat Semua Lowongan
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Statistics --}}
    <section class="py-20 bg-indigo-600">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @php
            $totalJobs = \App\Models\Job::where('status', 'published')
                ->where(function($query) {
                    $query->whereNull('application_deadline')
                          ->orWhere('application_deadline', '>=', now());
                })
                ->count();
            
            $totalEmployers = \App\Models\Employer::whereHas('user', function($query) {
                $query->where('role', 'employer');
            })->count();
            
            $totalSeekers = \App\Models\Seeker::whereHas('user', function($query) {
                $query->where('role', 'seeker');
            })->count();
            @endphp

            <div class="grid grid-cols-1 gap-8 text-center text-white md:grid-cols-3">
                <div>
                    <div class="mb-2 text-5xl font-bold">{{ number_format($totalJobs) }}+</div>
                    <div class="text-xl opacity-90">Lowongan Tersedia</div>
                </div>
                <div>
                    <div class="mb-2 text-5xl font-bold">{{ number_format($totalEmployers) }}+</div>
                    <div class="text-xl opacity-90">Rekruter Terdaftar</div>
                </div>
                <div>
                    <div class="mb-2 text-5xl font-bold">{{ number_format($totalSeekers) }}+</div>
                    <div class="text-xl opacity-90">Kandidat Terdaftar</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <h2 class="mb-4 text-4xl font-bold text-gray-900">Testimoni Dari Pengguna Kami</h2>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                @php
                $testimonials = [
                    ['name' => 'Sari Dewi', 'role' => 'Marketing Manager', 'title' => 'Mudah Mencari Kandidat Berkualitas', 'text' => 'AdoJobs.id membantu saya menemukan kandidat terbaik untuk tim marketing. Fitur pencarian yang canggih dan sistem aplikasi yang efisien membuat proses rekrutmen menjadi lebih mudah.'],
                    ['name' => 'Budi Santoso', 'role' => 'Software Engineer', 'title' => 'Platform Terbaik untuk Job Seeker', 'text' => 'Melalui AdoJobs.id, saya berhasil mendapatkan pekerjaan impian di perusahaan teknologi. Interface yang user-friendly dan fitur resume publik sangat membantu dalam proses pencarian kerja.'],
                    ['name' => 'Rina Wijaya', 'role' => 'HR Manager', 'title' => 'Sistem Rekrutmen yang Efektif', 'text' => 'AdoJobs.id telah mengubah cara kami melakukan rekrutmen. Fitur messaging langsung dengan kandidat dan sistem tracking aplikasi membuat proses seleksi menjadi lebih transparan dan efisien.'],
                ];
                @endphp

                @foreach($testimonials as $testimonial)
                <div class="p-8 bg-gray-50 rounded-2xl">
                    <h3 class="mb-4 text-xl font-bold text-indigo-600">{{ $testimonial['title'] }}</h3>
                    <p class="mb-6 text-gray-600">{{ $testimonial['text'] }}</p>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full"></div>
                        <div class="ml-4">
                            <div class="font-semibold text-gray-900">{{ $testimonial['name'] }}</div>
                            <div class="text-sm text-gray-600">{{ $testimonial['role'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-12 text-center bg-white rounded-3xl shadow-2xl">
                <h2 class="mb-4 text-4xl font-bold text-gray-900">Rekrutmen?</h2>
                <p class="mx-auto mb-8 max-w-2xl text-xl text-gray-600">
                    Iklankan pekerjaan Anda kepada ratusan pengguna bulanan dan telusuri ratusan CV di database kami.
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center px-8 py-4 font-semibold text-white bg-indigo-600 rounded-xl shadow-lg transition hover:bg-indigo-700">
                    Mulailah Rekrutmen Sekarang
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <x-footer />

</body>
</html>
