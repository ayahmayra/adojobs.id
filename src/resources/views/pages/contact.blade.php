<x-layouts.app>
    <x-slot name="title">Hubungi Kami - {{ site_name() }}</x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="px-4 py-8 mx-auto max-w-3xl text-center">
                <h1 class="text-4xl font-bold text-gray-900">Hubungi Kami</h1>
                <p class="mt-4 text-lg text-gray-600">
                    Halaman ini sedang dalam pengembangan. Silakan kembali lagi nanti.
                </p>
                <div class="mt-8">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-indigo-600 rounded-lg transition-colors hover:bg-indigo-700">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
