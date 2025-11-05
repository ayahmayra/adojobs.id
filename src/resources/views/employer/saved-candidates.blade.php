<x-layouts.dashboard>
    <x-slot name="title">Kandidat Favorit</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.employer />
    </x-slot>

    <x-slot name="header">
        Kandidat Favorit
    </x-slot>

    @if($savedCandidates->count() > 0)
        <div class="space-y-4">
            @foreach($savedCandidates as $savedCandidate)
            @php
                $seeker = $savedCandidate->seeker;
                $user = $seeker->user;
            @endphp
            <div class="p-6 bg-white rounded-lg shadow-sm transition hover:shadow-md">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-start flex-1">
                                <img class="object-cover w-16 h-16 rounded-full" 
                                     src="{{ $user->avatar_url }}" 
                                     alt="{{ $user->name }}">
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            <a href="{{ route('seekers.show', $seeker) }}" class="hover:text-indigo-600">
                                                {{ $user->name }}
                                            </a>
                                        </h3>
                                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </div>
                                    @if($seeker->current_job_title)
                                        <p class="mb-2 text-sm font-medium text-gray-700">{{ $seeker->current_job_title }}</p>
                                    @endif
                                    <p class="mb-2 text-gray-600">{{ $user->email }}</p>
                                    @if($seeker->phone)
                                        <p class="text-sm text-gray-500">{{ $seeker->phone }}</p>
                                    @endif
                                    @if($seeker->city || $seeker->state)
                                        <p class="mt-1 text-sm text-gray-500">
                                            📍 {{ $seeker->city }}{{ $seeker->city && $seeker->state ? ', ' : '' }}{{ $seeker->state }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex gap-3 items-start">
                                <span class="text-xs text-gray-500">
                                    Ditambahkan {{ $savedCandidate->created_at->diffForHumans() }}
                                </span>
                                
                                {{-- Remove Button --}}
                                <form action="{{ route('employer.saved-candidates.destroy', $savedCandidate) }}" method="POST" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Hapus kandidat dari favorit?')"
                                            class="text-red-600 transition hover:text-red-800"
                                            title="Hapus dari favorit">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.7 4.3c-.4-.4-1-.4-1.4 0L12 10.6 5.7 4.3c-.4-.4-1-.4-1.4 0s-.4 1 0 1.4L10.6 12l-6.3 6.3c-.4.4-.4 1 0 1.4.2.2.4.3.7.3s.5-.1.7-.3L12 13.4l6.3 6.3c.2.2.5.3.7.3s.5-.1.7-.3c.4-.4.4-1 0-1.4L13.4 12l6.3-6.3c.4-.4.4-1 0-1.4z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Bio Preview --}}
                        @if($seeker->bio)
                        <div class="mb-4">
                            <p class="mb-2 text-xs font-medium text-gray-700">Tentang:</p>
                            <p class="text-sm text-gray-600 line-clamp-3">
                                {{ Str::limit($seeker->bio, 200) }}
                            </p>
                        </div>
                        @endif

                        {{-- Skills Preview --}}
                        @if($seeker->skills && is_array($seeker->skills) && count($seeker->skills) > 0)
                        <div class="mb-4">
                            <p class="mb-2 text-xs font-medium text-gray-700">Skills:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(array_slice($seeker->skills, 0, 8) as $skill)
                                    <span class="px-2 py-1 text-xs font-medium text-indigo-700 bg-indigo-100 rounded">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                                @if(count($seeker->skills) > 8)
                                    <span class="px-2 py-1 text-xs text-gray-500">
                                        +{{ count($seeker->skills) - 8 }} lainnya
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Experience Preview --}}
                        @if($seeker->experience && is_array($seeker->experience) && count($seeker->experience) > 0)
                        <div class="mb-4">
                            <p class="mb-2 text-xs font-medium text-gray-700">Pengalaman:</p>
                            <div class="space-y-1">
                                @foreach(array_slice($seeker->experience, 0, 2) as $exp)
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">{{ $exp['position'] ?? 'N/A' }}</span>
                                        @if(isset($exp['company']))
                                            di {{ $exp['company'] }}
                                        @endif
                                        @if(isset($exp['duration']))
                                            ({{ $exp['duration'] }})
                                        @endif
                                    </p>
                                @endforeach
                                @if(count($seeker->experience) > 2)
                                    <p class="text-xs text-gray-500">
                                        +{{ count($seeker->experience) - 2 }} pengalaman lainnya
                                    </p>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Education Preview --}}
                        @if($seeker->education && is_array($seeker->education) && count($seeker->education) > 0)
                        <div class="mb-4">
                            <p class="mb-2 text-xs font-medium text-gray-700">Pendidikan:</p>
                            <div class="space-y-1">
                                @foreach(array_slice($seeker->education, 0, 2) as $edu)
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">{{ $edu['degree'] ?? 'N/A' }}</span>
                                        @if(isset($edu['institution']))
                                            - {{ $edu['institution'] }}
                                        @endif
                                        @if(isset($edu['year']))
                                            ({{ $edu['year'] }})
                                        @endif
                                    </p>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex flex-wrap gap-3 items-center pt-4 border-t border-gray-200">
                            <a href="{{ route('seekers.show', $seeker) }}" 
                               class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                                Lihat Profil Lengkap
                            </a>
                            
                            @if($user->resume_slug)
                            <a href="{{ route('resume.show', $user->resume_slug) }}" 
                               target="_blank"
                               class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg transition hover:bg-gray-50">
                                Lihat Resume
                            </a>
                            @endif

                            <a href="mailto:{{ $user->email }}" 
                               class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg transition hover:bg-gray-50">
                                Kirim Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $savedCandidates->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="p-12 text-center bg-white rounded-lg shadow-sm">
            <svg class="mx-auto mb-4 w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <h3 class="mb-2 text-lg font-semibold text-gray-900">Belum Ada Kandidat Favorit</h3>
            <p class="mb-6 text-gray-600">
                Mulai tambahkan kandidat yang menarik ke favorit untuk akses mudah nanti!
            </p>
            <a href="{{ route('employer.applications.index') }}" class="inline-block px-6 py-3 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                Lihat Semua Aplikasi
            </a>
        </div>
    @endif

</x-layouts.dashboard>

