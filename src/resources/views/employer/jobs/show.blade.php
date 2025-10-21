<x-layouts.dashboard>
    <x-slot name="title">Manage Job - {{ $job->title }}</x-slot>
    <x-slot name="sidebar">
        <x-sidebar.employer />
    </x-slot>

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('employer.jobs.index') }}" 
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to My Jobs
        </a>
    </div>

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $job->title }}</h1>
                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-800',
                            'published' => 'bg-green-100 text-green-800',
                            'closed' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$job->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($job->status) }}
                    </span>
                    @if($job->is_featured)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            ⭐ Featured
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $job->category->name }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ ucfirst($job->job_type) }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $job->city ?? 'Remote' }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Posted {{ $job->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('jobs.show', $job->slug) }}" 
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                View Public Page
            </a>
            <a href="{{ route('employer.jobs.edit', $job) }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-indigo-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Job
            </a>
            @if($job->status === 'published')
                <form action="{{ route('employer.jobs.update', $job) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="closed">
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to close this job posting?')"
                            class="inline-flex items-center px-4 py-2 bg-white border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 hover:bg-red-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Close Job
                    </button>
                </form>
            @elseif($job->status === 'closed')
                <form action="{{ route('employer.jobs.update', $job) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="published">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-white border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 hover:bg-green-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Reopen Job
                    </button>
                </form>
            @endif
            <form action="{{ route('employer.jobs.destroy', $job) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Are you sure you want to delete this job? This action cannot be undone.')"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $job->views_count ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Applications</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $job->applications_count }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Shortlisted</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $shortlistedCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hired</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $hiredCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Recent Applications --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Applications</h2>
                    <a href="{{ route('employer.applications.index', ['job_id' => $job->id]) }}" 
                       class="text-sm text-indigo-600 hover:text-indigo-900">
                        View All →
                    </a>
                </div>

                @if($recentApplications->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentApplications as $application)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                             src="{{ $application->seeker->user->avatar_url }}" 
                                             alt="{{ $application->seeker->user->name }}">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $application->seeker->user->name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Applied {{ $application->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'reviewed' => 'bg-blue-100 text-blue-800',
                                                'shortlisted' => 'bg-purple-100 text-purple-800',
                                                'interview' => 'bg-indigo-100 text-indigo-800',
                                                'offered' => 'bg-green-100 text-green-800',
                                                'hired' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                        <a href="{{ route('employer.applications.show', $application) }}" 
                                           class="text-sm text-indigo-600 hover:text-indigo-900">
                                            Review
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">No applications yet</p>
                    </div>
                @endif
            </div>

            {{-- Job Summary --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Summary</h2>
                
                <div class="space-y-4">
                    @if($job->description)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Description</h3>
                            <div class="text-sm text-gray-600 line-clamp-3">
                                {{ Str::limit(strip_tags($job->description), 200) }}
                            </div>
                        </div>
                    @endif

                    @if($job->requirements)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Requirements</h3>
                            <div class="text-sm text-gray-600 line-clamp-3">
                                {{ Str::limit(strip_tags($job->requirements), 200) }}
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('jobs.show', $job->slug) }}" 
                       target="_blank"
                       class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                        View full job details
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Job Details --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Details</h2>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($job->status) }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Work Mode</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($job->work_mode) }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Experience Level</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $job->experience_level ?? 'Not specified' }}</dd>
                    </div>
                    
                    @if($job->salary_min || $job->salary_max)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Salary Range</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($job->salary_negotiable)
                                    Negotiable
                                @else
                                    Rp {{ number_format($job->salary_min) }} - Rp {{ number_format($job->salary_max) }}
                                    <span class="text-xs text-gray-500">/ {{ $job->salary_period }}</span>
                                @endif
                            </dd>
                        </div>
                    @endif
                    
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Vacancies</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $job->vacancies }} position(s)</dd>
                    </div>
                    
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Application Deadline</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $job->application_deadline ? \Carbon\Carbon::parse($job->application_deadline)->format('d M Y') : 'No deadline' }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Posted Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $job->created_at->format('d M Y') }}</dd>
                    </div>
                    
                    @if($job->published_at)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Published Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $job->published_at->format('d M Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Skills Required --}}
            @if($job->required_skills && count($job->required_skills) > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Required Skills</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($job->required_skills as $skill)
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Application Stats by Status --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Applications by Status</h2>
                
                <div class="space-y-2">
                    @foreach(['pending', 'reviewed', 'shortlisted', 'interview', 'offered', 'hired', 'rejected'] as $status)
                        @php
                            $count = $job->applications->where('status', $status)->count();
                            $percentage = $job->applications_count > 0 ? ($count / $job->applications_count) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-600">{{ ucfirst($status) }}</span>
                                <span class="font-medium text-gray-900">{{ $count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>

