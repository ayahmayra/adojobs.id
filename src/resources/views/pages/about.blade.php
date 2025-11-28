<x-layouts.app>
    <x-slot name="title">Tentang Kami - {{ site_name() }}</x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="px-4 py-8 mx-auto max-w-4xl text-center">
                <h1 class="text-4xl font-bold text-gray-900">Tentang {{ site_name() }}</h1>
                <p class="mt-4 text-xl text-gray-600">
                    Platform pencarian kerja terbaik di Pulau Bengkalis
                </p>
            </div>

            <!-- Main Content -->
            <div class="px-4 py-8 mx-auto max-w-4xl">
                <div class="mx-auto prose prose-lg">
                    <h2 class="mb-6 text-2xl font-semibold text-gray-900">Visi & Misi</h2>
                    
                    <div class="p-6 mb-8 bg-indigo-50 rounded-lg">
                        <h3 class="mb-3 text-lg font-semibold text-indigo-900">Visi</h3>
                        <p class="text-indigo-800">
                            Menjadi platform pencarian kerja terdepan yang menghubungkan talenta terbaik dengan peluang karier berkualitas di Pulau Bengkalis dan sekitarnya.
                        </p>
                    </div>

                    <div class="p-6 mb-8 bg-green-50 rounded-lg">
                        <h3 class="mb-3 text-lg font-semibold text-green-900">Misi</h3>
                        <ul class="space-y-2 text-green-800">
                            <li>• Menyediakan akses mudah untuk pencari kerja menemukan lowongan yang sesuai</li>
                            <li>• Membantu perusahaan menemukan kandidat terbaik untuk posisi yang dibutuhkan</li>
                            <li>• Mendorong pertumbuhan ekonomi lokal melalui peningkatan kualitas SDM</li>
                            <li>• Menciptakan ekosistem kerja yang inklusif dan berkelanjutan</li>
                        </ul>
                    </div>

                    <h2 class="mb-6 text-2xl font-semibold text-gray-900">Tentang Platform</h2>
                    
                    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2">
                        <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="flex justify-center items-center mr-4 w-12 h-12 bg-indigo-100 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Untuk Pencari Kerja</h3>
                            </div>
                            <p class="text-gray-600">
                                Temukan lowongan kerja yang sesuai dengan keahlian dan minat Anda. Dari pekerjaan formal hingga pekerjaan lokal seperti pertanian, kebersihan, dan asisten rumah tangga.
                            </p>
                        </div>

                        <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="flex justify-center items-center mr-4 w-12 h-12 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Untuk Perusahaan</h3>
                            </div>
                            <p class="text-gray-600">
                                Pasang lowongan kerja dan temukan kandidat terbaik untuk perusahaan Anda. Dari startup hingga perusahaan besar, semua bisa menggunakan platform kami.
                            </p>
                        </div>
                    </div>

                    <h2 class="mb-6 text-2xl font-semibold text-gray-900">Fitur Utama</h2>
                    
                    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                        <div class="text-center">
                            <div class="flex justify-center items-center mx-auto mb-4 w-16 h-16 bg-blue-100 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Pencarian Cerdas</h3>
                            <p class="text-gray-600">Temukan lowongan dengan filter kategori, lokasi, dan tipe pekerjaan</p>
                        </div>

                        <div class="text-center">
                            <div class="flex justify-center items-center mx-auto mb-4 w-16 h-16 bg-purple-100 rounded-full">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Sistem Pesan</h3>
                            <p class="text-gray-600">Komunikasi langsung antara pencari kerja dan perusahaan</p>
                        </div>

                        <div class="text-center">
                            <div class="flex justify-center items-center mx-auto mb-4 w-16 h-16 bg-yellow-100 rounded-full">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Lowongan Favorit</h3>
                            <p class="text-gray-600">Simpan lowongan yang menarik untuk referensi nanti</p>
                        </div>
                    </div>

                    <h2 class="mb-6 text-2xl font-semibold text-gray-900">Komitmen Kami</h2>
                    
                    <div class="p-6 mb-8 bg-gray-50 rounded-lg">
                        <p class="leading-relaxed text-gray-700">
                            {{ site_name() }} berkomitmen untuk menjadi jembatan yang menghubungkan talenta terbaik dengan peluang karier berkualitas. 
                            Kami memahami bahwa setiap orang memiliki potensi unik, dan setiap perusahaan membutuhkan SDM yang tepat. 
                            Melalui platform ini, kami berusaha menciptakan ekosistem kerja yang saling menguntungkan, 
                            mendorong pertumbuhan ekonomi lokal, dan memberikan akses yang adil untuk semua.
                        </p>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-indigo-600 rounded-lg transition-colors hover:bg-indigo-700">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
