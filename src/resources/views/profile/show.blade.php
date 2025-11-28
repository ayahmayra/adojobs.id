<x-layouts.dashboard>
    <x-slot name="title">My Profile</x-slot>
    <x-slot name="sidebar">
        @if(Auth::user()->isAdmin())
            <x-sidebar.admin />
        @elseif(Auth::user()->isEmployer())
            <x-sidebar.employer />
        @elseif(Auth::user()->isSeeker())
            <x-sidebar.seeker />
        @endif
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
                <p class="mt-1 text-sm text-gray-600">Lihat dan kelola informasi pribadi Anda</p>
            </div>
            <div class="flex gap-3">
                @if($user->hasPublicResume())
                    <a href="{{ route('resume.show', $user->resume_slug) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 text-gray-700 bg-white rounded-md border border-gray-300 transition hover:bg-gray-50">
                        <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        View Public Resume
                    </a>
                @endif
                @if($user->isEmployer() && $user->employer)
                    <a href="{{ route('employers.show', $user->employer) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 text-gray-700 bg-white rounded-md border border-gray-300 transition hover:bg-gray-50">
                        <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        View Public Profile
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" 
                   class="inline-flex items-center px-4 py-2 text-white bg-indigo-600 rounded-md transition hover:bg-indigo-700">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profile
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl">
        {{-- Profile Card --}}
        <div class="overflow-hidden mb-6 bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-start">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0">
                        <img class="object-cover w-24 h-24 rounded-full border-4 border-gray-100" 
                             src="{{ $user->avatar_url }}" 
                             alt="{{ $user->name }}">
                    </div>

                    {{-- Basic Info --}}
                    <div class="flex-1 ml-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'employer' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Member Since</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase">Account Status</p>
                                <p class="mt-1 text-sm">
                                    @if($user->is_active)
                                        <span class="inline-flex items-center text-green-700">
                                            <svg class="mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-red-700">
                                            <svg class="mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Inactive
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Role-Specific Profile --}}
        @if($user->isSeeker() && $user->seeker)
            {{-- Seeker Profile --}}
            <div class="overflow-hidden mb-6 bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Seeker Information</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @if($user->seeker->phone)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->seeker->phone }}</dd>
                            </div>
                        @endif

                        @if($user->seeker->date_of_birth)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Date of Birth</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->seeker->date_of_birth)->format('F d, Y') }}</dd>
                            </div>
                        @endif

                        @if($user->seeker->gender)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Gender</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->seeker->gender) }}</dd>
                            </div>
                        @endif

                        @if($user->seeker->city)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Location</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->seeker->city }}, {{ $user->seeker->country ?? 'Indonesia' }}</dd>
                            </div>
                        @endif

                        @if($user->seeker->current_job_title)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Current Position</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->seeker->current_job_title }}</dd>
                            </div>
                        @endif

                        @if($user->seeker->job_type_preference)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Job Type Preference</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->seeker->job_type_preference) }}</dd>
                            </div>
                        @endif

                        @if($user->seeker->expected_salary_min || $user->seeker->expected_salary_max)
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium text-gray-500 uppercase">Expected Salary</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    Rp {{ number_format($user->seeker->expected_salary_min) }} - Rp {{ number_format($user->seeker->expected_salary_max) }}
                                </dd>
                            </div>
                        @endif

                        @if($user->seeker->bio)
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium text-gray-500 uppercase">Bio</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $user->seeker->bio }}</dd>
                            </div>
                        @endif

                        @if($user->seeker->skills && count($user->seeker->skills) > 0)
                            <div class="md:col-span-2">
                                <dt class="mb-2 text-xs font-medium text-gray-500 uppercase">Skills</dt>
                                <dd class="flex flex-wrap gap-2">
                                    @foreach($user->seeker->skills as $skill)
                                        <span class="px-3 py-1 text-xs font-medium text-indigo-800 bg-indigo-100 rounded-full">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                </dd>
                            </div>
                        @endif

                        @if($user->seeker->linkedin_url || $user->seeker->github_url || $user->seeker->portfolio_url)
                            <div class="md:col-span-2">
                                <dt class="mb-2 text-xs font-medium text-gray-500 uppercase">Links</dt>
                                <dd class="flex flex-wrap gap-3">
                                    @if($user->seeker->linkedin_url)
                                        <a href="{{ $user->seeker->linkedin_url }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                            <svg class="mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/>
                                            </svg>
                                            LinkedIn
                                        </a>
                                    @endif
                                    @if($user->seeker->github_url)
                                        <a href="{{ $user->seeker->github_url }}" target="_blank" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-800">
                                            <svg class="mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"/>
                                            </svg>
                                            GitHub
                                        </a>
                                    @endif
                                    @if($user->seeker->portfolio_url)
                                        <a href="{{ $user->seeker->portfolio_url }}" target="_blank" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                            Portfolio
                                        </a>
                                    @endif
                                </dd>
                            </div>
                        @endif

                        @if($user->seeker->preferred_location)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Preferred Location</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->seeker->preferred_location }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Work Experience --}}
            @if($user->seeker->experience && is_array($user->seeker->experience) && count($user->seeker->experience) > 0)
                <div class="overflow-hidden mb-6 bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Work Experience</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($user->seeker->experience as $exp)
                                <div class="relative pl-8 border-l-4 border-indigo-500">
                                    <div class="absolute top-0 -left-2.5 w-5 h-5 bg-indigo-600 rounded-full border-4 border-white"></div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $exp['job_title'] ?? 'N/A' }}</h4>
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
                                        <p class="mt-3 text-sm leading-relaxed text-gray-700">{{ $exp['description'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Education --}}
            @if($user->seeker->education && is_array($user->seeker->education) && count($user->seeker->education) > 0)
                <div class="overflow-hidden mb-6 bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Education</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($user->seeker->education as $edu)
                                <div class="relative pl-8 border-l-4 border-blue-500">
                                    <div class="absolute top-0 -left-2.5 w-5 h-5 bg-blue-600 rounded-full border-4 border-white"></div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $edu['degree'] ?? 'N/A' }}</h4>
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
                                        <p class="mt-3 text-sm leading-relaxed text-gray-700">{{ $edu['description'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        @elseif($user->isEmployer() && $user->employer)
            {{-- Employer Profile --}}
            
            {{-- Public Profile Link Banner --}}
            <div class="overflow-hidden mb-6 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-sm">
                <div class="p-6 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-12 h-12 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Your Public Company Profile</h3>
                                <p class="mt-1 text-sm text-indigo-100">Share your company profile with job seekers</p>
                            </div>
                        </div>
                        <a href="{{ route('employers.show', $user->employer) }}" 
                           target="_blank"
                           class="inline-flex items-center px-6 py-3 font-medium text-indigo-600 bg-white rounded-lg transition hover:bg-indigo-50">
                            <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            View Public Profile
                        </a>
                    </div>
                    <div class="p-3 mt-4 bg-white bg-opacity-20 rounded-lg">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center text-sm">
                                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Profile URL: <span class="ml-2 font-mono">{{ $user->employer->public_profile_url }}</span></span>
                            </div>
                            <button onclick="toggleSlugEdit()" 
                                    class="text-xs text-indigo-200 transition hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                        
                        {{-- Slug Edit Form --}}
                        <div id="slug-edit-form" class="hidden pt-3 mt-3 border-t border-white border-opacity-20">
                            <form action="{{ route('profile.employer.slug.update') }}" method="POST" class="flex gap-2">
                                @csrf
                                @method('PATCH')
                                <div class="flex-1">
                                <input type="text" 
                                       name="slug" 
                                       value="{{ $user->employer->slug ?: Str::slug($user->employer->company_name) }}"
                                       placeholder="company-slug"
                                       class="px-3 py-2 w-full text-sm text-black bg-white bg-opacity-90 rounded border-0 focus:ring-2 focus:ring-white focus:ring-opacity-50"
                                       pattern="[a-z0-9-]+"
                                       title="Only lowercase letters, numbers, and hyphens allowed">
                                </div>
                                <button type="submit" 
                                        class="px-3 py-2 text-xs font-medium text-indigo-600 bg-white rounded transition hover:bg-indigo-50">
                                    Update
                                </button>
                                <button type="button" 
                                        onclick="toggleSlugEdit()"
                                        class="px-3 py-2 text-xs font-medium text-gray-600 bg-white bg-opacity-50 rounded transition hover:bg-opacity-70">
                                    Cancel
                                </button>
                            </form>
                            <p class="mt-1 text-xs text-indigo-100">
                                Only lowercase letters, numbers, and hyphens allowed. Leave empty to auto-generate from company name.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Company Statistics --}}
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-4">
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Jobs</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->employer->jobs()->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active Jobs</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->employer->activeJobs()->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Applications</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ \App\Models\Application::whereIn('job_id', $user->employer->jobs->pluck('id'))->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($user->employer->is_verified)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                @endif
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Status</p>
                            <p class="text-sm font-bold {{ $user->employer->is_verified ? 'text-green-600' : 'text-gray-600' }}">
                                {{ $user->employer->is_verified ? 'Verified' : 'Unverified' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Company Information --}}
            <div class="overflow-hidden mb-6 bg-white rounded-lg shadow-sm">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Company Information</h3>
                    @if($user->employer->is_verified)
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                            <svg class="mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Verified Company
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Company Name</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">{{ $user->employer->company_name }}</dd>
                        </div>

                        @if($user->employer->company_website)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Website</dt>
                                <dd class="mt-1 text-sm">
                                    <a href="{{ $user->employer->company_website }}" target="_blank" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                                        {{ $user->employer->company_website }}
                                        <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </dd>
                            </div>
                        @endif

                        @if($user->employer->industry)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Industry</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->employer->industry }}</dd>
                            </div>
                        @endif

                        @if($user->employer->company_size)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Company Size</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->employer->company_size }}</dd>
                            </div>
                        @endif

                        @if($user->employer->founded_year)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Founded Year</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->employer->founded_year }}</dd>
                            </div>
                        @endif

                        @if($user->employer->city)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Location</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $user->employer->city }}@if($user->employer->state), {{ $user->employer->state }}@endif, {{ $user->employer->country ?? 'Indonesia' }}
                                </dd>
                            </div>
                        @endif

                        @if($user->employer->contact_phone)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Contact Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->employer->contact_phone }}</dd>
                            </div>
                        @endif

                        @if($user->employer->contact_email)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Contact Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->employer->contact_email }}</dd>
                            </div>
                        @endif

                        @if($user->employer->contact_person)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Contact Person</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->employer->contact_person }}</dd>
                            </div>
                        @endif

                        @if($user->employer->postal_code)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase">Postal Code</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->employer->postal_code }}</dd>
                            </div>
                        @endif

                        @if($user->employer->address)
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium text-gray-500 uppercase">Full Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->employer->full_address }}</dd>
                            </div>
                        @endif

                        @if($user->employer->company_description)
                            <div class="md:col-span-2">
                                <dt class="text-xs font-medium text-gray-500 uppercase">About Company</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $user->employer->company_description }}</dd>
                            </div>
                        @endif

                        {{-- Social Media Links --}}
                        @if($user->employer->linkedin_url || $user->employer->twitter_url || $user->employer->facebook_url)
                            <div class="md:col-span-2">
                                <dt class="mb-3 text-xs font-medium text-gray-500 uppercase">Social Media</dt>
                                <dd class="flex flex-wrap gap-3">
                                    @if($user->employer->linkedin_url)
                                        <a href="{{ $user->employer->linkedin_url }}" target="_blank" 
                                           class="inline-flex items-center px-4 py-2 text-blue-700 bg-blue-50 rounded-lg transition hover:bg-blue-100">
                                            <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                            LinkedIn
                                        </a>
                                    @endif
                                    @if($user->employer->twitter_url)
                                        <a href="{{ $user->employer->twitter_url }}" target="_blank" 
                                           class="inline-flex items-center px-4 py-2 text-sky-700 bg-sky-50 rounded-lg transition hover:bg-sky-100">
                                            <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                            </svg>
                                            Twitter
                                        </a>
                                    @endif
                                    @if($user->employer->facebook_url)
                                        <a href="{{ $user->employer->facebook_url }}" target="_blank" 
                                           class="inline-flex items-center px-4 py-2 text-blue-600 bg-blue-50 rounded-lg transition hover:bg-blue-100">
                                            <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                            Facebook
                                        </a>
                                    @endif
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

        @elseif($user->isAdmin())
            {{-- Admin Profile (Basic Info Only) --}}
            <div class="overflow-hidden mb-6 bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Administrator Information</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600">
                        You are logged in as an administrator. You have full access to manage the platform.
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- JavaScript for slug editing --}}
    <script>
        function toggleSlugEdit() {
            const form = document.getElementById('slug-edit-form');
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
            } else {
                form.classList.add('hidden');
            }
        }
    </script>
</x-layouts.dashboard>

