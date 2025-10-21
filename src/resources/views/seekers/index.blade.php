<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Daftar Kandidat') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- Search & Filter Bar -->
        <div class="overflow-hidden p-6 mb-6 bg-white shadow-sm sm:rounded-lg">
            <form action="{{ route('seekers.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama, posisi, keterampilan..." 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="skill" 
                            value="{{ request('skill') }}"
                            placeholder="Filter berdasarkan keterampilan..." 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="location" 
                            value="{{ request('location') }}"
                            placeholder="Filter berdasarkan lokasi..." 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>
                </div>
                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="px-6 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                    >
                        Cari
                    </button>
                    @if(request('search') || request('skill') || request('location'))
                        <a 
                            href="{{ route('seekers.index') }}" 
                            class="px-6 py-2 ml-3 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Candidates Grid -->
        @if($seekers->count() > 0)
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($seekers as $seeker)
                    <div class="overflow-hidden p-6 bg-white shadow-sm transition sm:rounded-lg hover:shadow-md">
                        {{-- User Avatar & Info --}}
                        <div class="flex gap-4 items-start mb-4">
                            <div class="flex-shrink-0">
                                @if($seeker->user->avatar_url)
                                    <img 
                                        src="{{ $seeker->user->avatar_url }}" 
                                        alt="{{ $seeker->user->name }}" 
                                        class="object-cover w-16 h-16 rounded-full"
                                    >
                                @else
                                    <div class="flex justify-center items-center w-16 h-16 bg-indigo-100 rounded-full">
                                        <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="mb-1 text-lg font-semibold text-gray-900 truncate">
                                    <a href="{{ route('seekers.show', $seeker) }}" class="hover:text-indigo-600">
                                        {{ $seeker->user->name }}
                                    </a>
                                </h3>
                                @if($seeker->current_job_title)
                                    <p class="text-sm font-medium text-indigo-600">{{ $seeker->current_job_title }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Bio --}}
                        @if($seeker->bio)
                            <p class="mb-4 text-sm text-gray-600 line-clamp-3">
                                {{ Str::limit($seeker->bio, 120) }}
                            </p>
                        @endif

                        {{-- Location & Preferences --}}
                        <div class="mb-4 space-y-2 text-sm text-gray-600">
                            @if($seeker->city || $seeker->country)
                                <div>📍 {{ $seeker->city }}{{ $seeker->city && $seeker->country ? ', ' : '' }}{{ $seeker->country }}</div>
                            @endif
                            @if($seeker->job_type_preference)
                                <div>💼 {{ $seeker->job_type_preference }}</div>
                            @endif
                            @if($seeker->expected_salary_min && $seeker->expected_salary_max)
                                <div>💰 Rp {{ number_format($seeker->expected_salary_min, 0, ',', '.') }} - Rp {{ number_format($seeker->expected_salary_max, 0, ',', '.') }}</div>
                            @endif
                        </div>

                        {{-- Skills --}}
                        @if($seeker->skills && is_array($seeker->skills) && count($seeker->skills) > 0)
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($seeker->skills, 0, 3) as $skill)
                                        <span class="px-2 py-1 text-xs text-indigo-800 bg-indigo-100 rounded">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                    @if(count($seeker->skills) > 3)
                                        <span class="px-2 py-1 text-xs text-gray-800 bg-gray-100 rounded">
                                            +{{ count($seeker->skills) - 3 }} lainnya
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <a 
                                href="{{ route('seekers.show', $seeker) }}" 
                                class="flex-1 px-3 py-2 text-sm text-center text-indigo-600 rounded-md border border-indigo-600 hover:bg-indigo-50"
                            >
                                Lihat Profil
                            </a>
                            @auth
                                @if(auth()->user()->isEmployer())
                                    <form action="{{ route('messages.start') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $seeker->user_id }}">
                                        <button type="submit"
                                                class="px-3 py-2 w-full text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                            Kirim Pesan
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $seekers->links() }}
            </div>
        @else
            <div class="overflow-hidden p-12 text-center bg-white shadow-sm sm:rounded-lg">
                <p class="text-gray-600">
                    @if(request('search') || request('skill') || request('location'))
                        Tidak ada kandidat yang sesuai dengan kriteria pencarian Anda.
                    @else
                        Belum ada kandidat yang terdaftar saat ini.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
</x-layouts.app>

