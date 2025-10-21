<x-layouts.dashboard>
    <x-slot name="title">Edit Profil</x-slot>
    <x-slot name="sidebar">
        @if(Auth::user()->isAdmin())
            <x-sidebar.admin />
        @elseif(Auth::user()->isEmployer())
            <x-sidebar.employer />
        @elseif(Auth::user()->isSeeker())
            <x-sidebar.seeker />
        @endif
    </x-slot>

    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Profil</h1>
                <p class="mt-1 text-sm text-gray-600">Perbarui informasi pribadi dan pengaturan Anda</p>
            </div>
            <a href="{{ route('profile.show') }}" 
               class="inline-flex items-center px-4 py-2 text-gray-700 bg-white rounded-md border border-gray-300 transition hover:bg-gray-50">
                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Profil
            </a>
        </div>

        <div class="space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Role-Specific Information --}}
            @if(Auth::user()->isSeeker())
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Pencari Kerja</h3>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-seeker-information-form')
                    </div>
                </div>
            @elseif(Auth::user()->isEmployer())
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Perusahaan</h3>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-employer-information-form')
                    </div>
                </div>
            @endif

            {{-- Change Password --}}
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Ubah Kata Sandi</h3>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Hapus Akun</h3>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
