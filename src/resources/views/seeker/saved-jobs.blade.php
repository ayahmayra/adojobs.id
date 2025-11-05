<x-layouts.dashboard>
    <x-slot name="title">Saved Jobs</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.seeker />
    </x-slot>

    <x-slot name="header">
        Saved Jobs
    </x-slot>

    @if($savedJobs->count() > 0)
        <div class="space-y-4">
            @foreach($savedJobs as $savedJob)
            <div class="p-6 bg-white rounded-lg shadow-sm transition hover:shadow-md">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <h3 class="mb-1 text-lg font-semibold text-gray-900">
                                    <a href="{{ route('jobs.show', $savedJob->job->slug) }}" class="hover:text-indigo-600">
                                        {{ $savedJob->job->title }}
                                    </a>
                                </h3>
                                <p class="mb-3 text-gray-600">
                                    <a href="{{ route('employers.show', $savedJob->job->employer) }}" class="hover:text-indigo-600 hover:underline">
                                        {{ $savedJob->job->employer->company_name }}
                                    </a>
                                </p>
                            </div>

                            {{-- Remove Button --}}
                            <form action="{{ route('seeker.saved-jobs.destroy', $savedJob) }}" method="POST" class="ml-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Remove this job from saved list?')"
                                        class="text-red-600 transition hover:text-red-800"
                                        title="Remove from saved">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.7 4.3c-.4-.4-1-.4-1.4 0L12 10.6 5.7 4.3c-.4-.4-1-.4-1.4 0s-.4 1 0 1.4L10.6 12l-6.3 6.3c-.4.4-.4 1 0 1.4.2.2.4.3.7.3s.5-.1.7-.3L12 13.4l6.3 6.3c.2.2.5.3.7.3s.5-.1.7-.3c.4-.4.4-1 0-1.4L13.4 12l6.3-6.3c.4-.4.4-1 0-1.4z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        
                        <div class="flex flex-wrap gap-3 mb-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $savedJob->job->city }}
                            </span>
                            <span class="flex items-center">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ ucfirst(str_replace('-', ' ', $savedJob->job->job_type)) }}
                            </span>
                            <span class="flex items-center">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ ucfirst(str_replace('-', ' ', $savedJob->job->work_mode)) }}
                            </span>
                            @if($savedJob->job->category)
                            <span class="flex items-center">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $savedJob->job->category->name }}
                            </span>
                            @endif
                            <span class="flex items-center text-gray-500">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                                Saved {{ $savedJob->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Description --}}
                        <p class="mb-4 text-sm text-gray-600 line-clamp-2">
                            {{ Str::limit(strip_tags($savedJob->job->description), 150) }}
                        </p>

                        {{-- Salary --}}
                        @if($savedJob->job->salary_min && $savedJob->job->salary_max)
                        <div class="mb-4">
                            <span class="text-sm font-semibold text-green-600">
                                Rp {{ number_format($savedJob->job->salary_min / 1000000, 1) }}M - {{ number_format($savedJob->job->salary_max / 1000000, 1) }}M / {{ $savedJob->job->salary_period }}
                            </span>
                        </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex gap-3 items-center">
                            <a href="{{ route('jobs.show', $savedJob->job->slug) }}" 
                               class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                                View Details
                            </a>
                            
                            @if(auth()->user()->seeker && !auth()->user()->seeker->hasAppliedToJob($savedJob->job->id))
                            <a href="{{ route('seeker.applications.create', $savedJob->job) }}" 
                               class="px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                                Apply Now
                            </a>
                            @else
                            <span class="text-sm text-gray-500">Already Applied</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $savedJobs->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="p-12 text-center bg-white rounded-lg shadow-sm">
            <svg class="mx-auto mb-4 w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <h3 class="mb-2 text-lg font-semibold text-gray-900">No Saved Jobs Yet</h3>
            <p class="mb-6 text-gray-600">
                Start saving jobs you're interested in for easy access later!
            </p>
            <a href="{{ route('jobs.index') }}" class="inline-block px-6 py-3 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                Jelajahi Lowongan
            </a>
        </div>
    @endif

</x-layouts.dashboard>

