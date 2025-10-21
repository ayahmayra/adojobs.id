<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Daftar Rekruter') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

        <!-- Search Bar -->
        <div class="overflow-hidden p-6 mb-6 bg-white shadow-sm sm:rounded-lg">
            <form action="{{ route('employers.index') }}" method="GET" class="space-y-4">
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama perusahaan, deskripsi, atau lokasi..." 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="px-6 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                    >
                        Cari
                    </button>
                    @if(request('search'))
                        <a 
                            href="{{ route('employers.index') }}" 
                            class="px-6 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
                        >
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Employers Grid -->
        @if($employers->count() > 0)
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($employers as $employer)
                    <div class="overflow-hidden p-6 bg-white shadow-sm transition sm:rounded-lg hover:shadow-md">
                        {{-- Company Logo --}}
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center">
                                @if($employer->company_logo)
                                    <img 
                                        src="{{ Storage::url($employer->company_logo) }}" 
                                        alt="{{ $employer->company_name }}" 
                                        class="object-cover w-16 h-16 rounded-lg"
                                    >
                                @else
                                    <div class="flex justify-center items-center w-16 h-16 bg-indigo-100 rounded-lg">
                                        <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                @endif
                                @if($employer->is_verified)
                                    <div class="ml-2">
                                        <span class="inline-flex items-center text-blue-600" title="Terverifikasi">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Company Info --}}
                        <div class="mb-4">
                            <h3 class="mb-1 text-lg font-semibold text-gray-900">
                                <a href="{{ route('employers.show', $employer) }}" class="hover:text-indigo-600">
                                    {{ $employer->company_name }}
                                </a>
                            </h3>
                            @if($employer->industry)
                                <p class="mb-2 text-sm text-gray-500">{{ $employer->industry }}</p>
                            @endif
                            @if($employer->company_description)
                                <p class="text-sm text-gray-600 line-clamp-2">
                                    {{ Str::limit($employer->company_description, 120) }}
                                </p>
                            @endif
                        </div>

                        {{-- Company Details --}}
                        <div class="mb-4 space-y-2 text-sm text-gray-600">
                            @if($employer->city || $employer->country)
                                <div>📍 {{ $employer->city }}{{ $employer->city && $employer->country ? ', ' : '' }}{{ $employer->country }}</div>
                            @endif
                            @if($employer->company_size)
                                <div>👥 {{ $employer->company_size }} karyawan</div>
                            @endif
                            @if($employer->founded_year)
                                <div>📅 Berdiri tahun {{ $employer->founded_year }}</div>
                            @endif
                        </div>

                        {{-- Active Jobs Count --}}
                        <div class="text-sm text-gray-600">
                            <div class="mb-3">
                                {{ $employer->jobs()->where('status', 'published')->where('application_deadline', '>=', now())->count() }} lowongan aktif
                            </div>
                            <div class="flex gap-2">
                                <a 
                                    href="{{ route('employers.show', $employer) }}" 
                                    class="flex-1 px-3 py-2 text-sm text-center text-indigo-600 rounded-md border border-indigo-600 hover:bg-indigo-50"
                                >
                                    Lihat Profil
                                </a>
                                @auth
                                    @if(auth()->user()->isSeeker())
                                        <form action="{{ route('messages.start') }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="employer_id" value="{{ $employer->id }}">
                                            <button type="submit"
                                                    class="px-3 py-2 w-full text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                                Kirim Pesan
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $employers->links() }}
            </div>
        @else
            <div class="overflow-hidden p-12 text-center bg-white shadow-sm sm:rounded-lg">
                <p class="text-gray-600">
                    @if(request('search'))
                        Tidak ada rekruter yang sesuai dengan pencarian Anda.
                    @else
                        Belum ada rekruter yang terdaftar saat ini.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
</x-layouts.app>
