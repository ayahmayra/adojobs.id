@php
    use App\Models\Setting;
    $phone = Setting::get('site_phone', '+62 812-3456-7890');
    $email = Setting::get('site_email', 'info@adojobs.id');
    $address = Setting::get('site_address', "Jl. Raya Bengkalis No. 123\nKecamatan Bengkalis\nKabupaten Bengkalis, Riau 28711");
    $whatsapp = Setting::get('site_whatsapp', '6281234567890');
    $hoursMondayFriday = Setting::get('site_hours_monday_friday', '08:00 - 17:00 WIB');
    $hoursSaturday = Setting::get('site_hours_saturday', '08:00 - 12:00 WIB');
    $hoursSunday = Setting::get('site_hours_sunday', 'Tutup');
    
    // Format WhatsApp link
    $whatsappLink = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp);
@endphp

<x-layouts.app>
    <x-slot name="title">Hubungi Kami - {{ site_name() }}</x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="px-4 py-8 mx-auto max-w-4xl text-center">
                <h1 class="text-4xl font-bold text-gray-900">Hubungi Kami</h1>
                <p class="mt-4 text-xl text-gray-600">
                    Kami siap membantu Anda menemukan peluang karier terbaik
                </p>
            </div>

            <!-- Contact Information -->
            <div class="px-4 py-8 mx-auto max-w-4xl">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    <!-- Contact Details -->
                    <div class="space-y-6">
                        <h2 class="text-2xl font-semibold text-gray-900">Informasi Kontak</h2>
                        
                        <!-- Phone -->
                        <div class="flex items-start space-x-4">
                            <div class="flex justify-center items-center w-12 h-12 bg-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Telepon</h3>
                                <p class="text-gray-600">{{ $phone }}</p>
                                <p class="text-sm text-gray-500">Senin - Jumat: {{ $hoursMondayFriday }}</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start space-x-4">
                            <div class="flex justify-center items-center w-12 h-12 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Email</h3>
                                <p class="text-gray-600">{{ $email }}</p>
                                <p class="text-sm text-gray-500">Respon dalam 24 jam</p>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex items-start space-x-4">
                            <div class="flex justify-center items-center w-12 h-12 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Alamat</h3>
                                <p class="text-gray-600 whitespace-pre-line">{{ $address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Office Hours & Additional Info -->
                    <div class="space-y-6">
                        <h2 class="text-2xl font-semibold text-gray-900">Jam Operasional</h2>
                        
                        <div class="p-6 bg-gray-50 rounded-lg">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Senin - Jumat</span>
                                    <span class="font-medium text-gray-900">{{ $hoursMondayFriday }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Sabtu</span>
                                    <span class="font-medium text-gray-900">{{ $hoursSaturday }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Minggu</span>
                                    <span class="font-medium {{ strtolower($hoursSunday) === 'tutup' ? 'text-red-600' : 'text-gray-900' }}">{{ $hoursSunday }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-indigo-50 rounded-lg">
                            <h3 class="mb-3 text-lg font-semibold text-indigo-900">Butuh Bantuan Cepat?</h3>
                            <p class="mb-4 text-indigo-800">
                                Untuk pertanyaan mendesak atau bantuan teknis, silakan hubungi kami melalui WhatsApp.
                            </p>
                            <a href="{{ $whatsappLink }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg transition-colors hover:bg-green-700">
                                <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.198 1.871.12.571-.091 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                Chat WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="mt-12 text-center">
                    <div class="p-8 text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg">
                        <h3 class="mb-4 text-2xl font-bold">Siap Memulai Karier Anda?</h3>
                        <p class="mb-6 text-lg text-indigo-100">
                            Daftar sekarang dan temukan lowongan kerja terbaik di Pulau Bengkalis
                        </p>
                        <div class="flex flex-col gap-4 justify-center sm:flex-row">
                            <a href="{{ route('register') }}" 
                               class="inline-flex items-center px-6 py-3 text-base font-medium text-indigo-600 bg-white rounded-lg transition-colors hover:bg-gray-50">
                                Daftar Sekarang
                            </a>
                            <a href="{{ route('jobs.index') }}" 
                               class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-transparent rounded-lg border border-white transition-colors hover:bg-white hover:text-indigo-600">
                                Lihat Lowongan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="mt-8 text-center">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-6 py-3 text-base font-medium text-gray-600 bg-gray-100 rounded-lg transition-colors hover:bg-gray-200">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
