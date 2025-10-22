<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $employer->company_name }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Company Header -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <div class="flex gap-6 items-start mb-4">
                        <div class="flex-shrink-0">
                            @if($employer->company_logo)
                                <img 
                                    src="{{ Storage::url($employer->company_logo) }}" 
                                    alt="{{ $employer->company_name }}" 
                                    class="object-cover w-24 h-24 rounded-lg"
                                >
                            @else
                                <div class="flex justify-center items-center w-24 h-24 bg-indigo-100 rounded-lg">
                                    @if($employer->user->avatar)
                                        <img 
                                            src="{{ $employer->user->avatar_url }}" 
                                            alt="{{ $employer->user->name }}" 
                                            class="object-cover w-24 h-24 rounded-lg"
                                        >
                                    @else
                                        <div class="flex justify-center items-center w-24 h-24 bg-indigo-100 rounded-lg">
                                            <span class="text-2xl font-bold text-indigo-600">
                                                {{ substr($employer->user->name, 0, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap gap-3 items-center mb-2">
                                <h1 class="text-3xl font-bold text-gray-900">{{ $employer->company_name }}</h1>
                                
                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">
                                    <svg class="mr-1.5 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                    </svg>
                                    Recruiter
                                </span>
                                
                                @if($employer->is_verified)
                                    <span class="text-blue-600" title="Verified">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                            @if($employer->industry)
                                <p class="mb-4 text-lg text-gray-600">{{ $employer->industry }}</p>
                            @endif
                            <div class="flex flex-wrap gap-4 text-gray-600">
                                @if($employer->city || $employer->country)
                                    <span>📍 {{ $employer->city }}{{ $employer->city && $employer->country ? ', ' : '' }}{{ $employer->country }}</span>
                                @endif
                                @if($employer->company_size)
                                    <span>👥 {{ $employer->company_size }} karyawan</span>
                                @endif
                                @if($employer->founded_year)
                                    <span>📅 Berdiri tahun {{ $employer->founded_year }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($employer->company_website || $employer->linkedin_url || $employer->twitter_url || $employer->facebook_url)
                        <div class="flex gap-3 mt-4">
                            @if($employer->company_website)
                                <a href="{{ $employer->company_website }}" target="_blank" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                    Website
                                </a>
                            @endif
                            @if($employer->linkedin_url)
                                <a href="{{ $employer->linkedin_url }}" target="_blank" class="px-4 py-2 text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200">
                                    LinkedIn
                                </a>
                            @endif
                            @if($employer->twitter_url)
                                <a href="{{ $employer->twitter_url }}" target="_blank" class="px-4 py-2 text-sky-700 bg-sky-100 rounded-lg hover:bg-sky-200">
                                    Twitter
                                </a>
                            @endif
                            @if($employer->facebook_url)
                                <a href="{{ $employer->facebook_url }}" target="_blank" class="px-4 py-2 text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200">
                                    Facebook
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Company Description -->
                @if($employer->company_description)
                    <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                        <h2 class="mb-4 text-xl font-bold text-gray-900">Tentang Rekruter</h2>
                        <p class="text-gray-700 whitespace-pre-line">{{ $employer->company_description }}</p>
                    </div>
                @endif

                <!-- Active Jobs -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">
                        Lowongan Aktif ({{ $employer->jobs->count() }})
                    </h2>
                    
                    @if($employer->jobs->count() > 0)
                        <div class="space-y-4">
                            @foreach($employer->jobs as $job)
                                <div class="p-4 rounded-lg border border-gray-200 hover:border-indigo-300">
                                    <h3 class="mb-2 text-lg font-semibold text-gray-900">
                                        <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-indigo-600">
                                            {{ $job->title }}
                                        </a>
                                    </h3>
                                    <div class="flex flex-wrap gap-4 mb-3 text-sm text-gray-600">
                                        @if($job->city)
                                            <span>📍 {{ $job->city }}</span>
                                        @endif
                                        @if($job->job_type)
                                            <span>💼 {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</span>
                                        @endif
                                        @if($job->salary_range)
                                            <span>💰 {{ $job->salary_range }}</span>
                                        @endif
                                    </div>
                                    @if($job->description)
                                        <p class="mb-3 text-sm text-gray-600">
                                            {{ Str::limit($job->description, 150) }}
                                        </p>
                                    @endif
                                    <a 
                                        href="{{ route('jobs.show', $job->slug) }}" 
                                        class="text-sm text-indigo-600 hover:text-indigo-700"
                                    >
                                        Lihat Detail →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-8 text-center text-gray-500">Tidak ada lowongan aktif saat ini</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6 lg:col-span-1">
                <!-- Contact Info -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 font-bold text-gray-900">Informasi Kontak</h3>
                    <div class="space-y-3 text-sm">
                        @if($employer->contact_person)
                            <div>
                                <div class="text-gray-600">Contact Person</div>
                                <div class="font-semibold">{{ $employer->contact_person }}</div>
                            </div>
                        @endif
                        @if($employer->contact_email)
                            <div>
                                <div class="text-gray-600">Email</div>
                                <a href="mailto:{{ $employer->contact_email }}" class="text-indigo-600 hover:text-indigo-700">
                                    {{ $employer->contact_email }}
                                </a>
                            </div>
                        @endif
                        @if($employer->contact_phone)
                            <div>
                                <div class="text-gray-600">Telepon</div>
                                <a href="tel:{{ $employer->contact_phone }}" class="text-indigo-600 hover:text-indigo-700">
                                    {{ $employer->contact_phone }}
                                </a>
                            </div>
                        @endif
                        @if($employer->full_address)
                            <div>
                                <div class="text-gray-600">Alamat</div>
                                <div>{{ $employer->full_address }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 font-bold text-gray-900">Statistik</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Lowongan Aktif</span>
                            <span class="text-2xl font-bold text-indigo-600">
                                {{ $employer->jobs()->where('status', 'published')->where('application_deadline', '>=', now())->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Lowongan</span>
                            <span class="text-2xl font-bold text-gray-900">
                                {{ $employer->jobs()->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact CTA for Seeker -->
                @auth
                    @if(Auth::user()->isAdmin())
                        <div class="overflow-hidden p-6 text-white bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-sm">
                            <h3 class="mb-2 font-bold">Hubungi Rekruter</h3>
                            <p class="mb-4 text-sm text-purple-100">Kirim pesan ke rekruter ini sebagai admin</p>
                            <form action="{{ route('messages.start') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $employer->user_id }}">
                                <button type="submit"
                                    class="flex justify-center items-center px-4 py-2 w-full text-purple-600 bg-white rounded-lg hover:bg-purple-50">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Kirim Pesan
                                </button>
                            </form>
                        </div>
                    @elseif(Auth::user()->isSeeker())
                        <div class="overflow-hidden p-6 text-white bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-sm">
                            <h3 class="mb-2 font-bold">Tertarik bekerja di sini?</h3>
                            <p class="mb-4 text-sm text-indigo-100">Hubungi rekruter untuk mendiskusikan peluang kerja</p>
                            <form action="{{ route('messages.start') }}" method="POST">
                                @csrf
                                <input type="hidden" name="employer_id" value="{{ $employer->id }}">
                                <button type="submit"
                                    class="flex justify-center items-center px-4 py-2 w-full text-indigo-600 bg-white rounded-lg hover:bg-indigo-50">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Kirim Pesan ke Rekruter
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="overflow-hidden p-6 text-white bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-sm">
                        <h3 class="mb-2 font-bold">Tertarik bekerja di sini?</h3>
                        <p class="mb-4 text-sm text-indigo-100">Masuk atau daftar untuk mengetahui informasi lebih lanjut.</p>
                        <div class="flex gap-2">
                            <a href="{{ route('login') }}" 
                                class="flex justify-center items-center px-4 py-2 w-full text-indigo-600 bg-white rounded-lg hover:bg-indigo-50">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" 
                                class="flex justify-center items-center px-4 py-2 w-full text-white rounded-lg border border-white hover:bg-white hover:text-indigo-600">
                                Daftar
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
