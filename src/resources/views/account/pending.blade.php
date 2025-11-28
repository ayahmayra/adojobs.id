<x-layouts.guest>
    <x-slot name="title">Menunggu Aktivasi Akun</x-slot>

    <div class="mb-6 text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-yellow-100">
            <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Akun Belum Diaktifkan</h2>
        <p class="mt-2 text-sm text-gray-600">Akun Anda sedang menunggu aktivasi dari admin</p>
    </div>

    <!-- Success Message from Registration -->
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex">
            <svg class="flex-shrink-0 w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="overflow-hidden bg-white rounded-lg shadow-sm">
        <div class="p-6 space-y-4">
            <div class="flex items-start">
                <svg class="flex-shrink-0 w-5 h-5 mt-0.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">Status Akun</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Akun Anda telah berhasil dibuat, namun belum diaktifkan oleh admin. 
                        Anda akan dapat login setelah admin mengaktifkan akun Anda.
                    </p>
                </div>
            </div>

            @if($userEmail)
            <div class="flex items-start">
                <svg class="flex-shrink-0 w-5 h-5 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                </svg>
                <div class="ml-3">
                    <p class="text-sm text-gray-600">
                        Email: <span class="font-medium text-gray-900">{{ $userEmail }}</span>
                    </p>
                </div>
            </div>
            @endif

            <div class="flex items-start">
                <svg class="flex-shrink-0 w-5 h-5 mt-0.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-gray-900">Hubungi Admin</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Jika Anda sudah mendaftar lebih dari 24 jam dan akun belum diaktifkan, 
                        silakan hubungi admin untuk mempercepat proses aktivasi.
                    </p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 space-y-3">
            {{-- WhatsApp Button --}}
            <a href="{{ $whatsappLink }}" 
               target="_blank"
               class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white bg-green-600 rounded-lg transition hover:bg-green-700">
                <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                Hubungi Admin via WhatsApp
            </a>

            {{-- Phone Button --}}
            @if($adminPhone)
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $adminPhone) }}" 
               class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg transition hover:bg-gray-50">
                <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                Hubungi Admin: {{ $adminPhone }}
            </a>
            @endif

            {{-- Email Button --}}
            @if($adminEmail)
            <a href="mailto:{{ $adminEmail }}" 
               class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg transition hover:bg-gray-50">
                <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Kirim Email: {{ $adminEmail }}
            </a>
            @endif
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
            ← Kembali ke halaman login
        </a>
    </div>
</x-layouts.guest>

