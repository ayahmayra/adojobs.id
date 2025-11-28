<x-layouts.dashboard>
    <x-slot name="title">Job Details</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Lowongan</h1>
                <p class="mt-1 text-sm text-gray-600">Lihat detail lengkap lowongan pekerjaan</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Kembali
            </a>
        </div>
    </x-slot>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Job Details --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                {{-- Header --}}
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $job->title }}</h2>
                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ $job->employer ? $job->employer->company_name : 'Employer deleted' }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $job->city }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $job->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $job->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $job->status === 'closed' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                            @if($job->is_featured)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                ⭐ Featured
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Job Info --}}
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <div class="text-sm text-gray-500">Job Type</div>
                            <div class="font-medium text-gray-900">{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Category</div>
                            <div class="font-medium text-gray-900">{{ $job->category->name ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Salary Range</div>
                            <div class="font-medium text-gray-900">
                                @if($job->min_salary && $job->max_salary)
                                    Rp {{ number_format($job->min_salary) }} - {{ number_format($job->max_salary) }}
                                @else
                                    Negotiable
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Experience</div>
                            <div class="font-medium text-gray-900">{{ $job->experience_level }}</div>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Job Description</h3>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </div>

                {{-- Requirements --}}
                @if($job->requirements)
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Requirements</h3>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($job->requirements)) !!}
                    </div>
                </div>
                @endif

                {{-- Benefits --}}
                @if($job->benefits)
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Benefits</h3>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($job->benefits)) !!}
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Statistics --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Applications</span>
                        <span class="text-2xl font-bold text-indigo-600">{{ $job->applications->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Posted</span>
                        <span class="text-sm text-gray-900">{{ $job->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Deadline</span>
                        <span class="text-sm text-gray-900">
                            {{ $job->deadline ? $job->deadline->format('M d, Y') : 'No deadline' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Employer Info --}}
            @if($job->employer)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Employer</h3>
                <div class="space-y-3">
                    <div>
                        <div class="text-sm text-gray-500">Company</div>
                        <div class="font-medium text-gray-900">{{ $job->employer->company_name }}</div>
                    </div>
                    @if($job->employer->user)
                    <div>
                        <div class="text-sm text-gray-500">Contact Person</div>
                        <div class="font-medium text-gray-900">{{ $job->employer->user->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Email</div>
                        <div class="font-medium text-gray-900">{{ $job->employer->user->email }}</div>
                    </div>
                    @else
                    <div>
                        <div class="text-sm text-gray-500 italic">Contact information unavailable (user deleted)</div>
                    </div>
                    @endif
                    @if($job->employer->company_website)
                    <div>
                        <div class="text-sm text-gray-500">Website</div>
                        <a href="{{ $job->employer->company_website }}" target="_blank" 
                           class="font-medium text-indigo-600 hover:text-indigo-800">
                            Visit Website →
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Employer</h3>
                <div class="text-sm text-gray-500 italic">Employer information unavailable (deleted)</div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('jobs.show', $job->slug) }}" target="_blank"
                       class="block w-full bg-indigo-600 text-white text-center px-4 py-2 rounded-lg hover:bg-indigo-700">
                        View on Site
                    </a>
                    <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="block w-full bg-red-600 text-white text-center px-4 py-2 rounded-lg hover:bg-red-700"
                                onclick="return confirm('Are you sure you want to delete this job?')">
                            Delete Job
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Applications --}}
    @if($job->applications->count() > 0)
    <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Applications ({{ $job->applications->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($job->applications as $application)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $application->seeker->user->avatar_url }}" 
                             alt="{{ $application->seeker->user->name }}" 
                             class="h-12 w-12 rounded-full object-cover">
                        <div>
                            <div class="font-medium text-gray-900">{{ $application->seeker->user->name }}</div>
                            <div class="text-sm text-gray-600">{{ $application->seeker->user->email }}</div>
                            <div class="text-xs text-gray-500 mt-1">Applied {{ $application->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $application->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $application->status === 'shortlisted' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $application->status === 'hired' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                </div>
                @if($application->cover_letter)
                <div class="mt-4 pl-16">
                    <div class="text-sm text-gray-600">
                        <strong>Cover Letter:</strong>
                        <p class="mt-1">{{ Str::limit($application->cover_letter, 200) }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

</x-layouts.dashboard>

