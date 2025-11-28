<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Home') }}
        </h2>
    </x-slot>

<div class="py-12">
    <!-- Hero Section -->
    <div class="mx-auto mb-12 max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-12 text-center">
                <h1 class="mb-4 text-4xl font-bold text-gray-900">
                    Find Your Dream Job Today
                </h1>
                <p class="mb-8 text-xl text-gray-600">
                    Connect with top employers and take the next step in your career
                </p>
                
                <!-- Search Form -->
                <form action="{{ route('jobs.index') }}" method="GET" class="mx-auto max-w-2xl">
                    <div class="flex gap-2">
                        <input type="text" name="keyword" 
                               placeholder="Job title, keywords, or company" 
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <input type="text" name="location" 
                               placeholder="Location" 
                               class="w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="submit" 
                                class="px-8 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="mx-auto mb-12 max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="overflow-hidden p-6 text-center bg-white shadow-sm sm:rounded-lg">
                <div class="text-3xl font-bold text-indigo-600">{{ number_format($stats['total_jobs']) }}</div>
                <div class="mt-2 text-gray-600">Active Jobs</div>
            </div>
            <div class="overflow-hidden p-6 text-center bg-white shadow-sm sm:rounded-lg">
                <div class="text-3xl font-bold text-indigo-600">{{ number_format($stats['total_companies']) }}</div>
                <div class="mt-2 text-gray-600">Companies</div>
            </div>
            <div class="overflow-hidden p-6 text-center bg-white shadow-sm sm:rounded-lg">
                <div class="text-3xl font-bold text-indigo-600">{{ number_format($stats['total_categories']) }}</div>
                <div class="mt-2 text-gray-600">Categories</div>
            </div>
        </div>
    </div>

    <!-- Featured Jobs -->
    @if($featuredJobs->count() > 0)
    <div class="mx-auto mb-12 max-w-7xl sm:px-6 lg:px-8">
        <h2 class="mb-6 text-2xl font-bold text-gray-900">Featured Jobs</h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($featuredJobs as $job)
            <div class="overflow-hidden p-6 bg-white shadow-sm transition sm:rounded-lg hover:shadow-md">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-indigo-600">
                                {{ $job->title }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-600">
                            <a href="{{ route('employers.show', $job->employer) }}" class="hover:text-indigo-600 hover:underline">
                                {{ $job->employer->company_name }}
                            </a>
                        </p>
                    </div>
                    <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded">Featured</span>
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                    <div>📍 {{ $job->city }}</div>
                    <div>💼 {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</div>
                    @if($job->salary_range)
                    <div>💰 {{ $job->salary_range }}</div>
                    @endif
                </div>
                <div class="mt-4">
                    <a href="{{ route('jobs.show', $job->slug) }}" 
                       class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        View Details →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Browse by Category -->
    @if($categories->count() > 0)
    <div class="mx-auto mb-12 max-w-7xl sm:px-6 lg:px-8">
        <h2 class="mb-6 text-2xl font-bold text-gray-900">Browse by Category</h2>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            @foreach($categories as $category)
            <a href="{{ route('jobs.index', ['category' => $category->id]) }}" 
               class="overflow-hidden p-6 text-center bg-white shadow-sm transition sm:rounded-lg hover:shadow-md">
                <div class="mb-2 text-3xl">{{ $category->icon ?? '📁' }}</div>
                <div class="font-semibold text-gray-900">{{ $category->name }}</div>
                <div class="mt-1 text-sm text-gray-600">{{ $category->jobs_count }} jobs</div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Jobs -->
    @if($recentJobs->count() > 0)
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h2 class="mb-6 text-2xl font-bold text-gray-900">Recent Job Openings</h2>
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="divide-y divide-gray-200">
                @foreach($recentJobs as $job)
                <div class="p-6 transition hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-indigo-600">
                                    {{ $job->title }}
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                <a href="{{ route('employers.show', $job->employer) }}" class="hover:text-indigo-600 hover:underline">
                                    {{ $job->employer->company_name }}
                                </a>
                            </p>
                            <div class="flex gap-4 mt-3 text-sm text-gray-500">
                                <span>📍 {{ $job->city }}</span>
                                <span>💼 {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</span>
                                <span>🏢 {{ ucfirst(str_replace('-', ' ', $job->work_mode)) }}</span>
                            </div>
                        </div>
                        <div class="ml-4 text-right">
                            @if($job->salary_range)
                            <div class="text-sm font-semibold text-gray-900">{{ $job->salary_range }}</div>
                            @endif
                            <div class="mt-1 text-xs text-gray-500">
                                Posted {{ $job->published_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="mt-6 text-center">
            <a href="{{ route('jobs.index') }}" 
               class="inline-block px-6 py-3 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                View All Jobs
            </a>
        </div>
    </div>
    @endif
</div>
</x-layouts.app>
