<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $job->title }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Job Header -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="mb-2 text-3xl font-bold text-gray-900">{{ $job->title }}</h1>
                            <p class="text-xl text-gray-600">
                                <a href="{{ route('employers.show', $job->employer) }}" class="transition-colors hover:text-indigo-600">
                                    {{ $job->employer->company_name }}
                                </a>
                            </p>
                        </div>
                        @if($job->is_featured)
                        <span class="px-3 py-1 text-yellow-800 bg-yellow-100 rounded">Unggulan</span>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-4 mb-6 text-gray-600">
                        <span>📍 {{ $job->location ?? $job->city }}</span>
                        <span>💼 {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</span>
                        <span>🏢 {{ ucfirst(str_replace('-', ' ', $job->work_mode)) }}</span>
                        @if($job->category)
                        <span>📁 {{ $job->category->name }}</span>
                        @endif
                    </div>

                    @auth
                        @if(auth()->user()->isSeeker())
                        <div class="flex gap-3">
                            @if(auth()->user()->seeker && auth()->user()->seeker->hasAppliedToJob($job->id))
                            <button disabled class="px-6 py-3 text-gray-600 bg-gray-300 rounded-md cursor-not-allowed">
                                Sudah Melamar
                            </button>
                            @else
                            <a href="{{ route('seeker.applications.create', $job) }}" 
                               class="px-6 py-3 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                Lamar Sekarang
                            </a>
                            @endif
                            
                            {{-- Message Employer Button - Always show for seekers --}}
                            <form action="{{ route('messages.start') }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="job_id" value="{{ $job->id }}">
                                @php
                                    $application = auth()->user()->seeker && auth()->user()->seeker->hasAppliedToJob($job->id) 
                                        ? auth()->user()->seeker->applications()->where('job_id', $job->id)->first() 
                                        : null;
                                @endphp
                                @if($application)
                                    <input type="hidden" name="application_id" value="{{ $application->id }}">
                                @endif
                                <button type="submit"
                                        class="flex items-center px-6 py-3 text-gray-700 rounded-md border border-gray-300 transition hover:bg-gray-50">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Kirim Pesan ke Rekruter
                                </button>
                            </form>
                        </div>
                        @endif
                    @else
                    <div class="p-4 bg-blue-50 rounded-md border border-blue-200">
                        <p class="text-blue-800">
                            <a href="{{ route('login') }}" class="font-semibold underline">Masuk</a> 
                            atau 
                            <a href="{{ route('register') }}" class="font-semibold underline">daftar</a> 
                            untuk melamar pekerjaan ini.
                        </p>
                    </div>
                    @endauth
                </div>

                <!-- Job Description -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Deskripsi Pekerjaan</h2>
                    <div class="max-w-none text-gray-700 prose">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </div>

                <!-- Requirements -->
                @if($job->requirements)
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Persyaratan</h2>
                    <div class="max-w-none text-gray-700 prose">
                        {!! nl2br(e($job->requirements)) !!}
                    </div>
                </div>
                @endif

                <!-- Responsibilities -->
                @if($job->responsibilities)
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Tanggung Jawab</h2>
                    <div class="max-w-none text-gray-700 prose">
                        {!! nl2br(e($job->responsibilities)) !!}
                    </div>
                </div>
                @endif

                <!-- Benefits -->
                @if($job->benefits)
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">Benefit</h2>
                    <div class="max-w-none text-gray-700 prose">
                        {!! nl2br(e($job->benefits)) !!}
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6 lg:col-span-1">
                <!-- Job Details -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 font-bold text-gray-900">Detail Lowongan</h3>
                    <div class="space-y-3 text-sm">
                        @if($job->salary_range)
                        <div>
                            <div class="text-gray-600">Gaji</div>
                            <div class="font-semibold">{{ $job->salary_range }}</div>
                        </div>
                        @endif

                        @if($job->experience_level)
                        <div>
                            <div class="text-gray-600">Tingkat Pengalaman</div>
                            <div class="font-semibold">{{ $job->experience_level }}</div>
                        </div>
                        @endif

                        @if($job->education_level)
                        <div>
                            <div class="text-gray-600">Pendidikan</div>
                            <div class="font-semibold">{{ $job->education_level }}</div>
                        </div>
                        @endif

                        <div>
                            <div class="text-gray-600">Posisi Tersedia</div>
                            <div class="font-semibold">{{ $job->vacancies }} posisi</div>
                        </div>

                        <div>
                            <div class="text-gray-600">Batas Waktu</div>
                            <div class="font-semibold">{{ $job->application_deadline->format('d M Y') }}</div>
                        </div>

                        <div>
                            <div class="text-gray-600">Diposting</div>
                            <div class="font-semibold">{{ $job->published_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Company Info -->
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 font-bold text-gray-900">Tentang {{ $job->employer->company_name }}</h3>
                    @if($job->employer->company_description)
                    <p class="mb-4 text-sm text-gray-600">{{ Str::limit($job->employer->company_description, 200) }}</p>
                    @endif
                    <div class="space-y-2 text-sm text-gray-600">
                        @if($job->employer->industry)
                        <div>📊 {{ $job->employer->industry }}</div>
                        @endif
                        @if($job->employer->company_size)
                        <div>👥 {{ $job->employer->company_size }} karyawan</div>
                        @endif
                        @if($job->employer->city)
                        <div>📍 {{ $job->employer->city }}</div>
                        @endif
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('employers.show', $job->employer) }}" 
                           class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                            Lihat Profil Perusahaan →
                        </a>
                    </div>
                </div>

                <!-- Skills -->
                @if($job->required_skills && count($job->required_skills) > 0)
                <div class="overflow-hidden p-6 bg-white shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 font-bold text-gray-900">Keterampilan yang Dibutuhkan</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($job->required_skills as $skill)
                        <span class="px-3 py-1 text-xs text-indigo-800 bg-indigo-100 rounded-full">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Similar Jobs -->
        @if($similarJobs->count() > 0)
        <div class="mt-12">
            <h2 class="mb-6 text-2xl font-bold text-gray-900">Lowongan Serupa</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach($similarJobs as $similarJob)
                <div class="overflow-hidden p-4 bg-white shadow-sm transition sm:rounded-lg hover:shadow-md">
                    <h3 class="mb-2 font-semibold text-gray-900">
                        <a href="{{ route('jobs.show', $similarJob->slug) }}" class="hover:text-indigo-600">
                            {{ Str::limit($similarJob->title, 40) }}
                        </a>
                    </h3>
                    <p class="mb-3 text-sm text-gray-600">
                        <a href="{{ route('employers.show', $similarJob->employer) }}" class="hover:text-indigo-600">
                            {{ $similarJob->employer->company_name }}
                        </a>
                    </p>
                    <div class="space-y-1 text-xs text-gray-500">
                        <div>📍 {{ $similarJob->city }}</div>
                        <div>💼 {{ ucfirst(str_replace('-', ' ', $similarJob->job_type)) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
</x-layouts.app>
