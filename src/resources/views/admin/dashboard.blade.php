<x-layouts.dashboard>
    <x-slot name="title">Admin Dashboard</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        Admin Dashboard
    </x-slot>

    <!-- Quick Alerts Banner -->
    @if($alerts['expired_jobs'] > 0 || $alerts['pending_applications'] > 0 || $alerts['draft_jobs'] > 0)
    <div class="p-4 mb-6 bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg border border-amber-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1 ml-3">
                <h3 class="text-sm font-medium text-amber-800">Action Required</h3>
                <div class="mt-2 space-y-1 text-sm text-amber-700">
                    @if($alerts['pending_applications'] > 0)
                    <p>• <strong>{{ $alerts['pending_applications'] }}</strong> pending applications need review</p>
                    @endif
                    @if($alerts['expired_jobs'] > 0)
                    <p>• <strong>{{ $alerts['expired_jobs'] }}</strong> jobs have expired deadlines</p>
                    @endif
                    @if($alerts['draft_jobs'] > 0)
                    <p>• <strong>{{ $alerts['draft_jobs'] }}</strong> jobs are still in draft status</p>
                    @endif
                    @if($alerts['inactive_employers'] > 0)
                    <p>• <strong>{{ $alerts['inactive_employers'] }}</strong> employers inactive for 60+ days</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- User Statistics Row -->
    {{-- <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
        <div class="overflow-hidden p-6 text-white bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <div class="text-sm font-medium text-blue-100">Total Users</div>
                    <div class="mt-2 text-3xl font-bold">{{ number_format($stats['total_users']) }}</div>
                    <div class="mt-2 text-xs text-blue-100">
                        +{{ $stats['new_users_this_month'] }} this month
                    </div>
                </div>
                <div class="p-3 bg-blue-400 bg-opacity-30 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg border-l-4 border-green-500 shadow-lg">
            <div class="text-sm font-medium text-gray-600">Employers</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total_employers']) }}</div>
            <div class="flex items-center mt-2">
                <span class="inline-block px-2 py-1 text-xs font-semibold text-green-600 bg-green-100 rounded-full">
                    {{ $stats['employers_percentage'] }}% of users
                </span>
            </div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg border-l-4 border-purple-500 shadow-lg">
            <div class="text-sm font-medium text-gray-600">Job Seekers</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total_seekers']) }}</div>
            <div class="flex items-center mt-2">
                <span class="inline-block px-2 py-1 text-xs font-semibold text-purple-600 bg-purple-100 rounded-full">
                    {{ $stats['seekers_percentage'] }}% of users
                </span>
            </div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg border-l-4 border-indigo-500 shadow-lg">
            <div class="text-sm font-medium text-gray-600">Active Users</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['active_users']) }}</div>
            <div class="mt-2 text-xs text-gray-500">
                Currently active accounts
            </div>
        </div>
    </div>

    <!-- Jobs Statistics Row -->
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-5">
        <div class="overflow-hidden p-6 text-white bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg">
            <div class="text-sm font-medium text-indigo-100">Total Jobs</div>
            <div class="mt-2 text-3xl font-bold">{{ number_format($stats['total_jobs']) }}</div>
            <div class="mt-2 text-xs text-indigo-100">+{{ $stats['jobs_this_week'] }} this week</div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg border-l-4 border-green-500 shadow-lg">
            <div class="text-sm font-medium text-gray-600">Active Jobs</div>
            <div class="mt-2 text-3xl font-bold text-green-600">{{ number_format($stats['active_jobs']) }}</div>
            <div class="mt-2 text-xs text-gray-500">Published & Open</div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg border-l-4 border-gray-500 shadow-lg">
            <div class="text-sm font-medium text-gray-600">Draft Jobs</div>
            <div class="mt-2 text-3xl font-bold text-gray-600">{{ number_format($stats['draft_jobs']) }}</div>
            <div class="mt-2 text-xs text-gray-500">Not published yet</div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg border-l-4 border-red-500 shadow-lg">
            <div class="text-sm font-medium text-gray-600">Closed Jobs</div>
            <div class="mt-2 text-3xl font-bold text-red-600">{{ number_format($stats['closed_jobs']) }}</div>
            <div class="mt-2 text-xs text-gray-500">No longer accepting</div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg border-l-4 border-blue-500 shadow-lg">
            <div class="text-sm font-medium text-gray-600">Avg Applications</div>
            <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['avg_applications_per_job'] }}</div>
            <div class="mt-2 text-xs text-gray-500">Per job posting</div>
        </div>
    </div>

    <!-- Applications Statistics Row -->
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3 lg:grid-cols-6">
        <div class="overflow-hidden p-6 text-white bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg">
            <div class="text-sm font-medium text-purple-100">Total Apps</div>
            <div class="mt-2 text-3xl font-bold">{{ number_format($stats['total_applications']) }}</div>
            <div class="mt-2 text-xs text-purple-100">All applications</div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg shadow-lg">
            <div class="text-xs font-medium text-gray-600">Pending</div>
            <div class="mt-1 text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_applications']) }}</div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg shadow-lg">
            <div class="text-xs font-medium text-gray-600">Reviewed</div>
            <div class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($stats['reviewed_applications']) }}</div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg shadow-lg">
            <div class="text-xs font-medium text-gray-600">Shortlisted</div>
            <div class="mt-1 text-2xl font-bold text-indigo-600">{{ number_format($stats['shortlisted_applications']) }}</div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg shadow-lg">
            <div class="text-xs font-medium text-gray-600">Hired</div>
            <div class="mt-1 text-2xl font-bold text-green-600">{{ number_format($stats['hired_applications']) }}</div>
        </div>

        <div class="overflow-hidden p-6 bg-white rounded-lg shadow-lg">
            <div class="text-xs font-medium text-gray-600">Rejected</div>
            <div class="mt-1 text-2xl font-bold text-red-600">{{ number_format($stats['rejected_applications']) }}</div>
        </div>
    </div> --}}

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
        <!-- Jobs & Applications Trend -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="mr-2 w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                    Jobs & Applications Trend (Last 7 Days)
                </h3>
            </div>
            <div class="p-6">
                <canvas id="trendChart" class="w-full" style="max-height: 300px;"></canvas>
            </div>
        </div>

        <!-- Jobs by Status -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="mr-2 w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                    </svg>
                    Jobs Distribution by Status
                </h3>
            </div>
            <div class="p-6">
                <canvas id="jobsStatusChart" class="w-full" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- More Charts Section -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
        <!-- Applications by Status -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="mr-2 w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Applications Status Breakdown
                </h3>
            </div>
            <div class="p-6">
                <canvas id="applicationsStatusChart" class="w-full" style="max-height: 300px;"></canvas>
            </div>
        </div>

        <!-- User Registration Trend -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="mr-2 w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    User Registrations (Last 7 Days)
                </h3>
            </div>
            <div class="p-6">
                <canvas id="usersChart" class="w-full" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-3">
        <!-- Recent Users -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Recent Users</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentUsers as $user)
                <div class="p-4 transition hover:bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                 class="object-cover w-10 h-10 rounded-full border-2 border-gray-200">
                            <div>
                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full font-semibold
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $user->role === 'employer' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $user->role === 'seeker' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-500">No users yet</div>
                @endforelse
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" class="flex justify-between items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    <span>View All Users</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                </a>
            </div>
        </div>

        <!-- Recent Jobs -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Recent Jobs</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentJobs as $job)
                <div class="p-4 transition hover:bg-gray-50">
                    <div class="font-medium text-gray-900">{{ Str::limit($job->title, 40) }}</div>
                    <div class="text-sm text-gray-600">{{ $job->employer->company_name }}</div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-xs text-gray-500">{{ $job->created_at->diffForHumans() }}</span>
                        <span class="px-2 py-1 text-xs rounded-full font-semibold
                            {{ $job->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $job->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $job->status === 'closed' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-500">No jobs yet</div>
                @endforelse
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                <a href="{{ route('admin.jobs.index') }}" class="flex justify-between items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    <span>View All Jobs</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                </a>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Recent Applications</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentApplications as $application)
                <div class="p-4 transition hover:bg-gray-50">
                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($application->job->title, 35) }}</div>
                    <div class="text-xs text-gray-600">{{ $application->seeker->user->name }}</div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</span>
                        <span class="px-2 py-1 text-xs rounded-full font-semibold
                            {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $application->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $application->status === 'shortlisted' ? 'bg-indigo-100 text-indigo-800' : '' }}
                            {{ $application->status === 'hired' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-500">No applications yet</div>
                @endforelse
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                <a href="#" class="flex justify-between items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    <span>View All Applications</span>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Top Performers Section -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Top Categories -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="mr-2 w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                    Top Categories
                </h3>
            </div>
            <div class="p-4">
                @forelse($topCategories as $index => $category)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex items-center space-x-3">
                        <div class="flex flex-shrink-0 justify-center items-center w-8 h-8 bg-indigo-100 rounded-full">
                            <span class="text-sm font-bold text-indigo-600">{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $category->name }}</div>
                            <div class="text-xs text-gray-500">{{ $category->jobs_count }} jobs</div>
                        </div>
                    </div>
                    <div class="text-2xl">{{ $category->icon }}</div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-500">No categories with jobs yet</div>
                @endforelse
            </div>
        </div>

        <!-- Top Employers -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="mr-2 w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                    </svg>
                    Top Employers
                </h3>
            </div>
            <div class="p-4">
                @forelse($topEmployers as $index => $employer)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex items-center space-x-3">
                        <div class="flex flex-shrink-0 justify-center items-center w-8 h-8 bg-green-100 rounded-full">
                            <span class="text-sm font-bold text-green-600">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 truncate">{{ $employer->company_name }}</div>
                            <div class="text-xs text-gray-500">{{ $employer->jobs_count }} jobs posted</div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-500">No employers with jobs yet</div>
                @endforelse
            </div>
        </div>

        <!-- Top Jobs by Applications -->
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                <h3 class="flex items-center text-lg font-semibold text-gray-800">
                    <svg class="mr-2 w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Top Jobs
                </h3>
            </div>
            <div class="p-4">
                @forelse($topJobs as $index => $job)
                <div class="py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex flex-1 items-start space-x-3 min-w-0">
                            <div class="flex flex-shrink-0 justify-center items-center w-8 h-8 bg-purple-100 rounded-full">
                                <span class="text-sm font-bold text-purple-600">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-900 truncate">{{ $job->title }}</div>
                                <div class="text-xs text-gray-500">{{ $job->employer->company_name }}</div>
                            </div>
                        </div>
                        <span class="px-2 py-1 ml-2 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">
                            {{ $job->applications_count }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-500">No jobs with applications yet</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart colors
            const colors = {
                primary: '#6366f1',
                secondary: '#8b5cf6',
                success: '#10b981',
                danger: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6',
                gray: '#6b7280',
                purple: '#a855f7',
                pink: '#ec4899'
            };

            // Jobs & Applications Trend Chart (Line Chart)
            const trendCtx = document.getElementById('trendChart');
            if (trendCtx) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [
                            {
                                label: 'Jobs Posted',
                                data: {!! json_encode($jobsTrend) !!},
                                borderColor: colors.primary,
                                backgroundColor: colors.primary + '20',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Applications',
                                data: {!! json_encode($applicationsTrend) !!},
                                borderColor: colors.success,
                                backgroundColor: colors.success + '20',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // Jobs Status Chart (Doughnut Chart)
            const jobsStatusCtx = document.getElementById('jobsStatusChart');
            if (jobsStatusCtx) {
                new Chart(jobsStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Published', 'Draft', 'Closed'],
                        datasets: [{
                            data: [
                                {{ $jobsByStatus['published'] }},
                                {{ $jobsByStatus['draft'] }},
                                {{ $jobsByStatus['closed'] }}
                            ],
                            backgroundColor: [
                                colors.success,
                                colors.gray,
                                colors.danger
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Applications Status Chart (Doughnut Chart)
            const applicationsStatusCtx = document.getElementById('applicationsStatusChart');
            if (applicationsStatusCtx) {
                new Chart(applicationsStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Reviewed', 'Shortlisted', 'Hired', 'Rejected'],
                        datasets: [{
                            data: [
                                {{ $applicationsByStatus['pending'] }},
                                {{ $applicationsByStatus['reviewed'] }},
                                {{ $applicationsByStatus['shortlisted'] }},
                                {{ $applicationsByStatus['hired'] }},
                                {{ $applicationsByStatus['rejected'] }}
                            ],
                            backgroundColor: [
                                colors.warning,
                                colors.info,
                                colors.primary,
                                colors.success,
                                colors.danger
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Users Registration Chart (Bar Chart)
            const usersCtx = document.getElementById('usersChart');
            if (usersCtx) {
                new Chart(usersCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            label: 'New Users',
                            data: {!! json_encode($usersTrend) !!},
                            backgroundColor: colors.info,
                            borderColor: colors.info,
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Registrations: ' + context.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush

</x-layouts.dashboard>
