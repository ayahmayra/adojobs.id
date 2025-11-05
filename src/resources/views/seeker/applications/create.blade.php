<x-layouts.dashboard>
    <x-slot name="title">Lamar Pekerjaan</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.seeker />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Lamar Pekerjaan</h1>
                <p class="mt-1 text-sm text-gray-600">Lengkapi formulir untuk melamar pekerjaan ini</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Application Form --}}
        <div class="lg:col-span-2">
            <div class="p-6 bg-white rounded-lg shadow-sm">
                <h2 class="mb-6 text-2xl font-bold text-gray-900">Kirim Lamaran Anda</h2>

                <form action="{{ route('seeker.jobs.apply', $job) }}" method="POST">
                    @csrf

                    {{-- Cover Letter --}}
                    <div class="mb-6">
                        <label for="cover_letter" class="block mb-2 text-sm font-medium text-gray-700">
                            Surat Lamaran *
                        </label>
                        <textarea 
                            name="cover_letter" 
                            id="cover_letter" 
                            rows="10" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('cover_letter') border-red-500 @enderror"
                            placeholder="Jelaskan mengapa Anda adalah kandidat yang tepat untuk posisi ini...">{{ old('cover_letter') }}</textarea>
                        @error('cover_letter')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Minimal 50 karakter. Jelaskan keahlian dan pengalaman Anda secara spesifik.</p>
                    </div>

                    {{-- Resume Info --}}
                    <div class="p-4 mb-6 bg-gray-50 rounded-lg">
                        <h3 class="mb-2 font-semibold text-gray-900">Resume Anda</h3>
                        @if(auth()->user()->seeker->cv_path)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="mr-2 w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Resume terlampir dari profil Anda
                            </div>
                        @else
                            <div class="flex items-center text-sm text-yellow-600">
                                <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Tidak ada resume tersimpan. 
                                <a href="{{ route('profile.edit') }}" class="ml-1 text-indigo-600 underline hover:text-indigo-800">
                                    Unggah resume Anda
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Terms --}}
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" required class="mt-1 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">
                                Saya menyatakan bahwa informasi yang saya berikan adalah benar dan saya setuju dengan 
                                <a href="{{ route('terms') }}" class="text-indigo-600 hover:text-indigo-800">syarat dan ketentuan</a>.
                            </span>
                        </label>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center space-x-3">
                        <button type="submit" class="px-8 py-3 font-medium text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                            Kirim Lamaran
                        </button>
                        <a href="{{ route('jobs.show', $job->slug) }}" class="px-8 py-3 font-medium text-gray-700 bg-gray-200 rounded-lg transition hover:bg-gray-300">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Job Summary Sidebar --}}
        <div class="lg:col-span-1">
            <div class="sticky top-6 p-6 bg-white rounded-lg shadow-sm">
                <h3 class="mb-4 font-bold text-gray-900">Ringkasan Lowongan</h3>
                
                <div class="space-y-4">
                    {{-- Job Title --}}
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $job->title }}</h4>
                        <p class="text-gray-600">{{ $job->employer->company_name }}</p>
                    </div>

                    {{-- Job Info --}}
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-gray-600">
                            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $job->city }}
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ ucfirst(str_replace('-', ' ', $job->work_mode)) }}
                        </div>
                    </div>

                    {{-- Salary --}}
                    @if($job->salary_min && $job->salary_max)
                    <div>
                        <div class="text-xs text-gray-500">Rentang Gaji</div>
                        <div class="font-semibold text-gray-900">
                            Rp {{ number_format($job->salary_min / 1000000, 1) }}M - {{ number_format($job->salary_max / 1000000, 1) }}M
                        </div>
                    </div>
                    @endif

                    {{-- Deadline --}}
                    @if($job->application_deadline)
                    <div>
                        <div class="text-xs text-gray-500">Batas Waktu Lamaran</div>
                        <div class="font-semibold text-gray-900">{{ $job->application_deadline->format('M d, Y') }}</div>
                    </div>
                    @endif
                </div>

                {{-- View Full Details --}}
                <div class="pt-6 mt-6 border-t border-gray-200">
                    <a href="{{ route('jobs.show', $job->slug) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Lihat Detail Lengkap Lowongan →
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-layouts.dashboard>

