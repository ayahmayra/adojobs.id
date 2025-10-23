<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $user->name }}'s Professional Resume - {{ $user->seeker->current_job_title ?? 'Job Seeker' }}">
    
    <title>{{ $user->name }} - Resume | AdoJobs.id</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=jost:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Jost', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    {{-- Header Navigation --}}
    <nav class="bg-white shadow-sm no-print">
        <div class="px-4 py-4 mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="flex items-center text-gray-600 hover:text-gray-900">
                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to AdoJobs.id
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 text-white bg-indigo-600 rounded-md transition hover:bg-indigo-700">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print / Download PDF
                </button>
            </div>
        </div>
    </nav>

    {{-- Resume Content --}}
    <div class="px-4 py-8 mx-auto max-w-4xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            {{-- Header --}}
            <div class="px-8 py-12 text-white bg-gradient-to-r from-indigo-600 to-purple-600">
                <div class="flex justify-between items-start">
                    <div class="flex items-center">
                        <img class="object-cover w-24 h-24 rounded-full border-4 border-white" 
                             src="{{ $user->avatar_url }}" 
                             alt="{{ $user->name }}">
                        <div class="ml-6">
                            <h1 class="text-4xl font-bold">{{ $user->name }}</h1>
                            @if($user->seeker->current_job_title)
                                <p class="mt-1 text-xl text-indigo-100">{{ $user->seeker->current_job_title }}</p>
                            @endif
                            @if($user->seeker->city)
                                <p class="flex items-center mt-2 text-indigo-200">
                                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $user->seeker->city }}, {{ $user->seeker->country ?? 'Indonesia' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- Contact Info --}}
                <div class="flex flex-wrap gap-4 mt-6 text-sm">
                    <a href="mailto:{{ $user->email }}" class="inline-flex items-center text-white hover:text-indigo-100">
                        <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $user->email }}
                    </a>
                    @if($user->seeker->phone)
                        <span class="inline-flex items-center text-white">
                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $user->seeker->phone }}
                        </span>
                    @endif
                    @if($user->seeker->linkedin_url)
                        <a href="{{ $user->seeker->linkedin_url }}" target="_blank" class="inline-flex items-center text-white hover:text-indigo-100">
                            <svg class="mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                            LinkedIn
                        </a>
                    @endif
                    @if($user->seeker->github_url)
                        <a href="{{ $user->seeker->github_url }}" target="_blank" class="inline-flex items-center text-white hover:text-indigo-100">
                            <svg class="mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"/>
                            </svg>
                            GitHub
                        </a>
                    @endif
                    @if($user->seeker->portfolio_url)
                        <a href="{{ $user->seeker->portfolio_url }}" target="_blank" class="inline-flex items-center text-white hover:text-indigo-100">
                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                            Portfolio
                        </a>
                    @endif
                </div>
            </div>

            {{-- Content --}}
            <div class="px-8 py-8">
                {{-- About / Bio --}}
                @if($user->seeker->bio)
                    <section class="mb-8">
                        <h2 class="pb-2 mb-4 text-2xl font-bold text-gray-900 border-b-2 border-indigo-600">About Me</h2>
                        <p class="leading-relaxed text-gray-700 whitespace-pre-line">{{ $user->seeker->bio }}</p>
                    </section>
                @endif

                {{-- Skills --}}
                @if($user->seeker->skills)
                    @php
                        // Handle both array and string formats
                        $skills = is_array($user->seeker->skills) ? $user->seeker->skills : json_decode($user->seeker->skills, true);
                        $skills = $skills ?: [];
                    @endphp
                    @if(count($skills) > 0)
                        <section class="mb-8">
                            <h2 class="pb-2 mb-4 text-2xl font-bold text-gray-900 border-b-2 border-indigo-600">Skills</h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach($skills as $skill)
                                    <span class="px-4 py-2 text-sm font-medium text-indigo-800 bg-indigo-100 rounded-lg">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endif

                {{-- Work Experience --}}
                @if($user->seeker->experience && is_array($user->seeker->experience) && count($user->seeker->experience) > 0)
                    <section class="mb-8">
                        <h2 class="pb-2 mb-4 text-2xl font-bold text-gray-900 border-b-2 border-indigo-600">Work Experience</h2>
                        <div class="space-y-6">
                            @foreach($user->seeker->experience as $exp)
                                <div class="relative pl-8 border-l-4 border-indigo-500">
                                    <div class="absolute top-0 -left-2 w-4 h-4 bg-indigo-600 rounded-full"></div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $exp['job_title'] ?? 'N/A' }}</h3>
                                    <p class="font-medium text-indigo-600 text-md">{{ $exp['company_name'] ?? 'N/A' }}</p>
                                    @if(isset($exp['start_date']))
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($exp['start_date'])->format('M Y') }} - 
                                            @if(!empty($exp['is_current']))
                                                <span class="font-medium text-indigo-600">Present</span>
                                            @elseif(isset($exp['end_date']))
                                                {{ \Carbon\Carbon::parse($exp['end_date'])->format('M Y') }}
                                            @else
                                                Present
                                            @endif
                                        </p>
                                    @endif
                                    @if(!empty($exp['description']))
                                        <p class="mt-2 leading-relaxed text-gray-700">{{ $exp['description'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Education --}}
                @if($user->seeker->education && is_array($user->seeker->education) && count($user->seeker->education) > 0)
                    <section class="mb-8">
                        <h2 class="pb-2 mb-4 text-2xl font-bold text-gray-900 border-b-2 border-indigo-600">Education</h2>
                        <div class="space-y-6">
                            @foreach($user->seeker->education as $edu)
                                <div class="relative pl-8 border-l-4 border-blue-500">
                                    <div class="absolute top-0 -left-2 w-4 h-4 bg-blue-600 rounded-full"></div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $edu['degree'] ?? 'N/A' }}</h3>
                                    <p class="font-medium text-blue-600 text-md">{{ $edu['institution'] ?? 'N/A' }}</p>
                                    @if(!empty($edu['field_of_study']))
                                        <p class="text-sm text-gray-600">{{ $edu['field_of_study'] }}</p>
                                    @endif
                                    @if(isset($edu['start_date']))
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($edu['start_date'])->format('Y') }} - 
                                            @if(!empty($edu['is_current']))
                                                <span class="font-medium text-blue-600">Present</span>
                                            @elseif(isset($edu['end_date']))
                                                {{ \Carbon\Carbon::parse($edu['end_date'])->format('Y') }}
                                            @else
                                                Present
                                            @endif
                                        </p>
                                    @endif
                                    @if(!empty($edu['description']))
                                        <p class="mt-2 leading-relaxed text-gray-700">{{ $edu['description'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Job Preferences --}}
                @if($user->seeker->job_type_preference || $user->seeker->expected_salary_min || $user->seeker->preferred_location)
                    <section class="mb-8">
                        <h2 class="pb-2 mb-4 text-2xl font-bold text-gray-900 border-b-2 border-indigo-600">Job Preferences</h2>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            @if($user->seeker->job_type_preference)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Job Type</p>
                                    <p class="mt-1 text-gray-900">{{ ucfirst($user->seeker->job_type_preference) }}</p>
                                </div>
                            @endif
                            @if($user->seeker->expected_salary_min && $user->seeker->expected_salary_max)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Expected Salary</p>
                                    <p class="mt-1 text-gray-900">
                                        Rp {{ number_format($user->seeker->expected_salary_min) }} - Rp {{ number_format($user->seeker->expected_salary_max) }}
                                    </p>
                                </div>
                            @endif
                            @if($user->seeker->preferred_location)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Preferred Location</p>
                                    <p class="mt-1 text-gray-900">{{ $user->seeker->preferred_location }}</p>
                                </div>
                            @endif
                        </div>
                    </section>
                @endif

                {{-- Download CV Button --}}
                @if($user->seeker->cv_path)
                    <section class="mb-8 no-print">
                        <a href="{{ Storage::url($user->seeker->cv_path) }}" 
                           target="_blank"
                           class="inline-flex items-center px-6 py-3 font-medium text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                            <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Full CV/Resume
                        </a>
                    </section>
                @endif
            </div>

            {{-- Footer --}}
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="text-sm text-center text-gray-600">
                    <p>This resume is hosted on <a href="{{ route('home') }}" class="font-medium text-indigo-600 hover:text-indigo-800">AdoJobs.id</a></p>
                    <p class="mt-1">Last updated: {{ $user->updated_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

