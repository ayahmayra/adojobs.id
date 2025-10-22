@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pengaturan Website</h1>
        <p class="mt-2 text-gray-600">Kelola pengaturan umum website AdoJobs.id</p>
    </div>

    <!-- Settings Cards -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- General Settings -->
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 text-indigo-600 bg-indigo-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pengaturan Umum</h3>
                    <p class="text-sm text-gray-600">Nama website, deskripsi, dan informasi dasar</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Nama Website</span>
                    <span class="text-sm text-gray-500">AdoJobs.id</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Deskripsi Website</span>
                    <span class="text-sm text-gray-500">Platform pencarian kerja terbaik</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Email Kontak</span>
                    <span class="text-sm text-gray-500">admin@adojobs.id</span>
                </div>
            </div>
            <div class="mt-4">
                <button class="px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                    Edit Pengaturan
                </button>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 text-green-600 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pengaturan Email</h3>
                    <p class="text-sm text-gray-600">Konfigurasi SMTP dan template email</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">SMTP Host</span>
                    <span class="text-sm text-gray-500">smtp.gmail.com</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">SMTP Port</span>
                    <span class="text-sm text-gray-500">587</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Email From</span>
                    <span class="text-sm text-gray-500">noreply@adojobs.id</span>
                </div>
            </div>
            <div class="mt-4">
                <button class="px-4 py-2 text-sm font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition">
                    Konfigurasi Email
                </button>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 text-purple-600 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pengaturan SEO</h3>
                    <p class="text-sm text-gray-600">Meta tags, keywords, dan optimasi SEO</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Meta Title</span>
                    <span class="text-sm text-gray-500">AdoJobs.id - Platform Pencarian Kerja</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Meta Description</span>
                    <span class="text-sm text-gray-500">Temukan pekerjaan impian Anda</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Keywords</span>
                    <span class="text-sm text-gray-500">lowongan kerja, karir, rekrutmen</span>
                </div>
            </div>
            <div class="mt-4">
                <button class="px-4 py-2 text-sm font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                    Edit SEO
                </button>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 text-red-600 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pengaturan Keamanan</h3>
                    <p class="text-sm text-gray-600">Password policy, 2FA, dan keamanan</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Password Policy</span>
                    <span class="text-sm text-gray-500">Minimal 8 karakter</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">2FA Required</span>
                    <span class="text-sm text-gray-500">Tidak</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Session Timeout</span>
                    <span class="text-sm text-gray-500">2 jam</span>
                </div>
            </div>
            <div class="mt-4">
                <button class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                    Konfigurasi Keamanan
                </button>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 text-yellow-600 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 00-15 0v5h5l-5 5-5-5h5v-5a7.5 7.5 0 0115 0v5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pengaturan Notifikasi</h3>
                    <p class="text-sm text-gray-600">Email, SMS, dan push notification</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Email Notifications</span>
                    <span class="text-sm text-gray-500">Aktif</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">SMS Notifications</span>
                    <span class="text-sm text-gray-500">Tidak Aktif</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Push Notifications</span>
                    <span class="text-sm text-gray-500">Aktif</span>
                </div>
            </div>
            <div class="mt-4">
                <button class="px-4 py-2 text-sm font-medium text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                    Kelola Notifikasi
                </button>
            </div>
        </div>

        <!-- System Settings -->
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 text-blue-600 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pengaturan Sistem</h3>
                    <p class="text-sm text-gray-600">Maintenance mode, cache, dan sistem</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Maintenance Mode</span>
                    <span class="text-sm text-gray-500">Tidak Aktif</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Cache Status</span>
                    <span class="text-sm text-gray-500">Aktif</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Debug Mode</span>
                    <span class="text-sm text-gray-500">Tidak Aktif</span>
                </div>
            </div>
            <div class="mt-4">
                <button class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    Kelola Sistem
                </button>
            </div>
        </div>
    </div>

    <!-- Coming Soon Notice -->
    <div class="mt-8 p-6 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg border border-indigo-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-indigo-900">Fitur Sedang Dikembangkan</h3>
                <p class="mt-1 text-sm text-indigo-700">
                    Halaman pengaturan ini sedang dalam tahap pengembangan. Fitur-fitur pengaturan akan segera tersedia untuk memberikan kontrol penuh atas website AdoJobs.id.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
