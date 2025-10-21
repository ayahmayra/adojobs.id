<x-layouts.dashboard>
    <x-slot name="title">My Applications</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.seeker />
    </x-slot>

    <x-slot name="header">
        My Applications
    </x-slot>

    {{-- Filter Form --}}
    <div class="p-4 mb-6 bg-white rounded-lg shadow-sm">
        <form method="GET" action="{{ route('seeker.applications.index') }}" class="flex flex-wrap gap-4">
            {{-- Status Filter --}}
            <div class="flex-1 min-w-[200px]">
                <label for="status" class="block mb-1 text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                    <option value="interview" {{ request('status') == 'interview' ? 'selected' : '' }}>Interview</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="withdrawn" {{ request('status') == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                </select>
            </div>

            {{-- Filter Buttons --}}
            <div class="flex gap-2 items-end">
                <button type="submit" class="px-6 py-2 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                    Filter
                </button>
                @if(request()->hasAny(['status']))
                <a href="{{ route('seeker.applications.index') }}" class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg transition hover:bg-gray-300">
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Applications List --}}
    @if($applications->count() > 0)
        <div class="space-y-4">
            @foreach($applications as $application)
            <div class="p-6 bg-white rounded-lg shadow-sm transition hover:shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h3 class="mb-1 text-lg font-semibold text-gray-900">
                            <a href="{{ route('jobs.show', $application->job->slug) }}" class="hover:text-indigo-600">
                                {{ $application->job->title }}
                            </a>
                        </h3>
                        <p class="mb-2 text-gray-600">
                            <a href="{{ route('employers.show', $application->job->employer) }}" class="hover:text-indigo-600 hover:underline">
                                {{ $application->job->employer->company_name }}
                            </a>
                        </p>
                        
                        <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                            <span class="flex items-center">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $application->job->city }}
                            </span>
                            <span class="flex items-center">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ ucfirst(str_replace('-', ' ', $application->job->job_type)) }}
                            </span>
                            <span class="flex items-center">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Applied {{ $application->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'reviewed' => 'bg-blue-100 text-blue-800',
                                'shortlisted' => 'bg-purple-100 text-purple-800',
                                'interview' => 'bg-indigo-100 text-indigo-800',
                                'accepted' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'withdrawn' => 'bg-gray-100 text-gray-800',
                            ];
                            $statusColor = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="{{ $statusColor }} px-3 py-1 rounded-full text-xs font-medium uppercase">
                            {{ $application->status }}
                        </span>
                    </div>
                </div>

                {{-- Cover Letter Preview --}}
                <div class="p-4 mb-4 bg-gray-50 rounded-lg">
                    <h4 class="mb-2 text-sm font-semibold text-gray-700">Cover Letter</h4>
                    <p class="text-sm text-gray-600 line-clamp-2">{{ $application->cover_letter }}</p>
                </div>

                {{-- Actions --}}
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <div class="flex gap-3">
                        <a href="{{ route('jobs.show', $application->job->slug) }}" 
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            View Job Details
                        </a>
                        @if($application->status === 'pending')
                        <button 
                            onclick="if(confirm('Are you sure you want to withdraw this application?')) { document.getElementById('withdraw-{{ $application->id }}').submit(); }"
                            class="text-sm font-medium text-red-600 hover:text-red-800">
                            Withdraw Application
                        </button>
                        <form id="withdraw-{{ $application->id }}" 
                              action="{{ route('seeker.applications.withdraw', $application) }}" 
                              method="POST" 
                              class="hidden">
                            @csrf
                            @method('PATCH')
                        </form>
                        @endif
                    </div>

                    {{-- Job Status --}}
                    @if($application->job->status !== 'published')
                    <span class="text-xs text-gray-500">
                        Job Status: {{ ucfirst($application->job->status) }}
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $applications->withQueryString()->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="p-12 text-center bg-white rounded-lg shadow-sm">
            <svg class="mx-auto mb-4 w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mb-2 text-lg font-semibold text-gray-900">No Applications Yet</h3>
            <p class="mb-6 text-gray-600">
                @if(request('status'))
                    No applications found with the selected status.
                @else
                    You haven't applied to any jobs yet. Start browsing jobs and apply!
                @endif
            </p>
            <div class="space-x-3">
                <a href="{{ route('seeker.jobs.index') }}" class="inline-block px-6 py-3 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                    Browse Jobs
                </a>
                @if(request('status'))
                <a href="{{ route('seeker.applications.index') }}" class="inline-block px-6 py-3 text-gray-700 bg-gray-200 rounded-lg transition hover:bg-gray-300">
                    Clear Filter
                </a>
                @endif
            </div>
        </div>
    @endif

</x-layouts.dashboard>

