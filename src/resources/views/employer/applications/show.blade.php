<x-layouts.dashboard>
    <x-slot name="title">Application Details - {{ $application->seeker->user->name }}</x-slot>
    <x-slot name="sidebar">
        <x-sidebar.employer />
    </x-slot>

    <div class="mb-6">
        <a href="{{ route('employer.applications.index') }}" 
           class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Applications
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main Content --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Candidate Info --}}
            <div class="p-6 bg-white rounded-lg shadow-sm">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center">
                        <img class="object-cover w-16 h-16 rounded-full" 
                             src="{{ $application->seeker->user->avatar_url }}" 
                             alt="{{ $application->seeker->user->name }}">
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $application->seeker->user->name }}</h1>
                            <p class="text-gray-600">{{ $application->seeker->user->email }}</p>
                            @if($application->seeker->phone)
                                <p class="text-gray-600">{{ $application->seeker->phone }}</p>
                            @endif
                        </div>
                    </div>
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
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>

                <div class="pt-6 border-t border-gray-200">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Applied For</h2>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-medium text-gray-900">{{ $application->job->title }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $application->job->category->name }}</p>
                        <div class="flex gap-4 items-center mt-2 text-sm text-gray-500">
                            <span>{{ ucfirst($application->job->job_type) }}</span>
                            <span>•</span>
                            <span>{{ ucfirst($application->job->work_mode) }}</span>
                            @if($application->job->city)
                                <span>•</span>
                                <span>{{ $application->job->city }}</span>
                            @endif
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Applied on {{ $application->created_at->format('d M Y \a\t H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Cover Letter --}}
            @if($application->cover_letter)
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Cover Letter</h2>
                    <div class="max-w-none text-gray-700 whitespace-pre-line prose prose-sm">
                        {{ $application->cover_letter }}
                    </div>
                </div>
            @endif

            {{-- Work Experience --}}
            @if($application->seeker->experience && is_array($application->seeker->experience) && count($application->seeker->experience) > 0)
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Work Experience</h2>
                    <div class="space-y-4">
                        @foreach($application->seeker->experience as $exp)
                            <div class="pl-4 border-l-4 border-indigo-500">
                                <h3 class="font-medium text-gray-900">{{ $exp['job_title'] ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-600">{{ $exp['company_name'] ?? 'N/A' }}</p>
                                @if(isset($exp['start_date']))
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($exp['start_date'])->format('M Y') }} - 
                                        @if(!empty($exp['is_current']))
                                            Present
                                        @elseif(isset($exp['end_date']))
                                            {{ \Carbon\Carbon::parse($exp['end_date'])->format('M Y') }}
                                        @else
                                            Present
                                        @endif
                                    </p>
                                @endif
                                @if(!empty($exp['description']))
                                    <p class="mt-2 text-sm text-gray-700">{{ $exp['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Education --}}
            @if($application->seeker->education && is_array($application->seeker->education) && count($application->seeker->education) > 0)
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Education</h2>
                    <div class="space-y-4">
                        @foreach($application->seeker->education as $edu)
                            <div class="pl-4 border-l-4 border-blue-500">
                                <h3 class="font-medium text-gray-900">{{ $edu['degree'] ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-600">{{ $edu['institution'] ?? 'N/A' }}</p>
                                @if(!empty($edu['field_of_study']))
                                    <p class="text-sm text-gray-600">{{ $edu['field_of_study'] }}</p>
                                @endif
                                @if(isset($edu['start_date']))
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($edu['start_date'])->format('Y') }} - 
                                        @if(!empty($edu['is_current']))
                                            Present
                                        @elseif(isset($edu['end_date']))
                                            {{ \Carbon\Carbon::parse($edu['end_date'])->format('Y') }}
                                        @else
                                            Present
                                        @endif
                                    </p>
                                @endif
                                @if(!empty($edu['description']))
                                    <p class="mt-2 text-sm text-gray-700">{{ $edu['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Employer Notes --}}
            @if($application->employer_notes)
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Internal Notes</h2>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $application->employer_notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Update Status --}}
            <div class="p-6 bg-white rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Update Status</h2>
                <form method="POST" action="{{ route('employer.applications.updateStatus', $application) }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-700">
                            Application Status
                        </label>
                        <select name="status" id="status" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="interview" {{ $application->status === 'interview' ? 'selected' : '' }}>Interview</option>
                            <option value="offered" {{ $application->status === 'offered' ? 'selected' : '' }}>Offered</option>
                            <option value="hired" {{ $application->status === 'hired' ? 'selected' : '' }}>Hired</option>
                            <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="message_to_seeker" class="block mb-2 text-sm font-medium text-gray-700">
                            Pesan untuk Kandidat
                        </label>
                        <textarea name="message_to_seeker" id="message_to_seeker" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Pesan yang akan dikirim ke kandidat (opsional)...">{{ old('message_to_seeker') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Pesan ini akan dikirim ke kandidat bersama notifikasi perubahan status</p>
                    </div>

                    <div class="mb-4">
                        <label for="employer_notes" class="block mb-2 text-sm font-medium text-gray-700">
                            Catatan Internal
                        </label>
                        <textarea name="employer_notes" id="employer_notes" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Catatan internal tentang kandidat ini...">{{ old('employer_notes', $application->employer_notes) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Catatan ini hanya terlihat oleh Anda (tidak akan dikirim ke kandidat)</p>
                    </div>

                    <button type="submit" 
                            class="px-4 py-2 w-full text-white bg-indigo-600 rounded-md transition hover:bg-indigo-700">
                        Update Status
                    </button>
                </form>
            </div>

            {{-- Timeline --}}
            <div class="p-6 bg-white rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Timeline</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex justify-center items-center w-8 h-8 bg-blue-100 rounded-full">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Application Submitted</p>
                            <p class="text-xs text-gray-500">{{ $application->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($application->reviewed_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex justify-center items-center w-8 h-8 bg-indigo-100 rounded-full">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Reviewed</p>
                                <p class="text-xs text-gray-500">{{ $application->reviewed_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($application->shortlisted_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex justify-center items-center w-8 h-8 bg-purple-100 rounded-full">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Shortlisted</p>
                                <p class="text-xs text-gray-500">{{ $application->shortlisted_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($application->interview_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex justify-center items-center w-8 h-8 bg-yellow-100 rounded-full">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Interview Scheduled</p>
                                <p class="text-xs text-gray-500">{{ $application->interview_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($application->hired_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex justify-center items-center w-8 h-8 bg-green-100 rounded-full">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Hired</p>
                                <p class="text-xs text-gray-500">{{ $application->hired_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($application->rejected_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex justify-center items-center w-8 h-8 bg-red-100 rounded-full">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Rejected</p>
                                <p class="text-xs text-gray-500">{{ $application->rejected_at->format('d M Y, H:i') }}</p>
                                @if($application->rejection_reason)
                                    <p class="mt-1 text-xs text-gray-600">{{ $application->rejection_reason }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Resume/CV --}}
            @if($application->seeker->cv_path)
                <div class="p-6 bg-white rounded-lg shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900">Resume/CV</h2>
                    <a href="{{ Storage::url($application->seeker->cv_path) }}" 
                       target="_blank"
                       class="flex justify-center items-center px-4 py-2 w-full text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 shadow-sm transition hover:bg-gray-50">
                        <svg class="mr-2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        View Resume
                    </a>
                </div>
            @endif

            {{-- Quick Actions --}}
            <div class="p-6 bg-white rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Quick Actions</h2>
                <div class="space-y-2">
                    {{-- Message Candidate Button --}}
                    <form action="{{ route('messages.start') }}" method="POST">
                        @csrf
                        <input type="hidden" name="job_id" value="{{ $application->job_id }}">
                        <input type="hidden" name="application_id" value="{{ $application->id }}">
                        <button type="submit"
                                class="flex justify-center items-center px-4 py-2 w-full text-sm font-medium text-white bg-indigo-600 rounded-md border border-indigo-300 shadow-sm transition hover:bg-indigo-700">
                            <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Message Candidate
                        </button>
                    </form>
                    
                    <a href="mailto:{{ $application->seeker->user->email }}" 
                       class="flex justify-center items-center px-4 py-2 w-full text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 shadow-sm transition hover:bg-gray-50">
                        <svg class="mr-2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Send Email
                    </a>
                    
                    <a href="{{ route('jobs.show', $application->job->slug) }}" 
                       target="_blank"
                       class="flex justify-center items-center px-4 py-2 w-full text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 shadow-sm transition hover:bg-gray-50">
                        <svg class="mr-2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        View Job Posting
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>

