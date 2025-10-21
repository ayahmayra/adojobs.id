<x-layouts.dashboard>
    <x-slot name="title">Apply for Job</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.seeker />
    </x-slot>

    <x-slot name="header">
        Apply for Job
    </x-slot>

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('jobs.show', $job->slug) }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Job Details
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Application Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Submit Your Application</h2>

                <form action="{{ route('seeker.jobs.apply', $job) }}" method="POST">
                    @csrf

                    {{-- Cover Letter --}}
                    <div class="mb-6">
                        <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-2">
                            Cover Letter *
                        </label>
                        <textarea 
                            name="cover_letter" 
                            id="cover_letter" 
                            rows="10" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('cover_letter') border-red-500 @enderror"
                            placeholder="Explain why you're the perfect fit for this position...">{{ old('cover_letter') }}</textarea>
                        @error('cover_letter')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Minimum 50 characters. Be specific about your skills and experience.</p>
                    </div>

                    {{-- Resume Info --}}
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Your Resume</h3>
                        @if(auth()->user()->seeker->cv_path)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Resume attached from your profile
                            </div>
                        @else
                            <div class="flex items-center text-sm text-yellow-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                No resume on file. 
                                <a href="{{ route('profile.edit') }}" class="ml-1 text-indigo-600 hover:text-indigo-800 underline">
                                    Upload your resume
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Terms --}}
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" required class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">
                                I confirm that the information provided is accurate and I agree to the 
                                <a href="#" class="text-indigo-600 hover:text-indigo-800">terms and conditions</a>.
                            </span>
                        </label>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center space-x-3">
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition font-medium">
                            Submit Application
                        </button>
                        <a href="{{ route('jobs.show', $job->slug) }}" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Job Summary Sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                <h3 class="font-bold text-gray-900 mb-4">Job Summary</h3>
                
                <div class="space-y-4">
                    {{-- Job Title --}}
                    <div>
                        <h4 class="font-semibold text-lg text-gray-900">{{ $job->title }}</h4>
                        <p class="text-gray-600">{{ $job->employer->company_name }}</p>
                    </div>

                    {{-- Job Info --}}
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $job->city }}
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ ucfirst(str_replace('-', ' ', $job->work_mode)) }}
                        </div>
                    </div>

                    {{-- Salary --}}
                    @if($job->salary_min && $job->salary_max)
                    <div>
                        <div class="text-xs text-gray-500">Salary Range</div>
                        <div class="font-semibold text-gray-900">
                            Rp {{ number_format($job->salary_min / 1000000, 1) }}M - {{ number_format($job->salary_max / 1000000, 1) }}M
                        </div>
                    </div>
                    @endif

                    {{-- Deadline --}}
                    @if($job->application_deadline)
                    <div>
                        <div class="text-xs text-gray-500">Application Deadline</div>
                        <div class="font-semibold text-gray-900">{{ $job->application_deadline->format('M d, Y') }}</div>
                    </div>
                    @endif
                </div>

                {{-- View Full Details --}}
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('jobs.show', $job->slug) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        View Full Job Details →
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-layouts.dashboard>

