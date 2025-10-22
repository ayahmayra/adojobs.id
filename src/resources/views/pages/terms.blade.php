<x-layouts.app>
    <x-slot name="title">Syarat & Ketentuan - {{ site_name() }}</x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="px-4 py-8 mx-auto max-w-4xl text-center">
                <h1 class="text-4xl font-bold text-gray-900">Syarat & Ketentuan</h1>
                <p class="mt-4 text-xl text-gray-600">
                    Ketentuan penggunaan platform {{ site_name() }}
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    Terakhir diperbarui: {{ date('d F Y') }}
                </p>
            </div>

            <!-- Terms Content -->
            <div class="px-4 py-8 mx-auto max-w-4xl">
                <div class="mx-auto prose prose-lg">
                    
                    <!-- Introduction -->
                    <div class="p-6 mb-8 bg-blue-50 rounded-lg">
                        <h2 class="mb-3 text-xl font-semibold text-blue-900">Pengantar</h2>
                        <p class="text-blue-800">
                            Dengan menggunakan platform {{ site_name() }}, Anda menyetujui untuk terikat oleh syarat dan ketentuan yang tercantum di bawah ini. 
                            Jika Anda tidak menyetujui syarat dan ketentuan ini, harap tidak menggunakan layanan kami.
                        </p>
                    </div>

                    <!-- 1. Definisi -->
                    <section class="mb-8">
                        <h2 class="mb-4 text-2xl font-semibold text-gray-900">1. Definisi</h2>
                        <div class="space-y-3 text-gray-700">
                            <p><strong>Platform</strong> mengacu pada website {{ site_name() }} dan semua layanan yang terkait.</p>
                            <p><strong>Pengguna</strong> adalah setiap individu yang mengakses dan menggunakan platform ini.</p>
                            <p><strong>Pencari Kerja</strong> adalah pengguna yang mencari lowongan pekerjaan.</p>
                            <p><strong>Perusahaan</strong> adalah pengguna yang memposting lowongan pekerjaan.</p>
                            <p><strong>Konten</strong> adalah semua informasi, data, teks, gambar, atau materi lainnya yang diposting di platform.</p>
                        </div>
                    </section>

                    <!-- 2. Akun Pengguna -->
                    <section class="mb-8">
                        <h2 class="mb-4 text-2xl font-semibold text-gray-900">2. Akun Pengguna</h2>
                        <div class="space-y-3 text-gray-700">
                            <p>2.1. Untuk menggunakan layanan kami, Anda harus membuat akun dengan informasi yang akurat dan lengkap.</p>
                            <p>2.2. Anda bertanggung jawab untuk menjaga kerahasiaan kata sandi dan semua aktivitas yang terjadi di akun Anda.</p>
                            <p>2.3. Anda harus berusia minimal 17 tahun untuk menggunakan platform ini.</p>
                            <p>2.4. Satu orang hanya diperbolehkan memiliki satu akun.</p>
                            <p>2.5. Kami berhak menonaktifkan akun yang melanggar ketentuan ini.</p>
                        </div>
                    </section>

                    <!-- 3. Penggunaan Platform -->
                    <section class="mb-8">
                        <h2 class="mb-4 text-2xl font-semibold text-gray-900">3. Penggunaan Platform</h2>
                        <div class="space-y-3 text-gray-700">
                            <h3 class="text-lg font-semibold text-gray-800">3.1. Yang Diperbolehkan:</h3>
                            <ul class="ml-6 space-y-2">
                                <li>• Mencari dan melamar lowongan pekerjaan</li>
                                <li>• Memosting lowongan pekerjaan yang sah</li>
                                <li>• Berkomunikasi dengan pengguna lain secara profesional</li>
                                <li>• Menggunakan fitur platform sesuai dengan tujuan yang dimaksudkan</li>
                            </ul>
                            
                            <h3 class="mt-4 text-lg font-semibold text-gray-800">3.2. Yang Dilarang:</h3>
                            <ul class="ml-6 space-y-2">
                                <li>• Memosting informasi palsu atau menyesatkan</li>
                                <li>• Menggunakan platform untuk tujuan ilegal atau tidak etis</li>
                                <li>• Spam, phishing, atau aktivitas merugikan lainnya</li>
                                <li>• Melanggar hak kekayaan intelektual pihak lain</li>
                                <li>• Menggunakan bot atau sistem otomatis tanpa izin</li>
                            </ul>
                        </div>
                    </section>

                    <!-- 4. Konten dan Informasi -->
                    <section class="mb-8">
                        <h2 class="mb-4 text-2xl font-semibold text-gray-900">4. Konten dan Informasi</h2>
                        <div class="space-y-3 text-gray-700">
                            <p>4.1. Anda bertanggung jawab penuh atas semua konten yang Anda posting di platform.</p>
                            <p>4.2. Konten yang Anda posting harus akurat, terkini, dan tidak melanggar hukum.</p>
                            <p>4.3. Kami berhak menghapus konten yang melanggar ketentuan ini tanpa pemberitahuan sebelumnya.</p>
                            <p>4.4. Anda memberikan kami lisensi untuk menggunakan konten Anda dalam rangka menyediakan layanan platform.</p>
                        </div>
                    </section>

                    <!-- 5. Privasi dan Data -->
                    <section class="mb-8">
                        <h2 class="mb-4 text-2xl font-semibold text-gray-900">5. Privasi dan Perlindungan Data</h2>
                        <div class="space-y-3 text-gray-700">
                            <p>5.1. Kami menghormati privasi Anda dan melindungi data pribadi sesuai dengan kebijakan privasi kami.</p>
                            <p>5.2. Data yang Anda berikan akan digunakan untuk menyediakan layanan platform dan meningkatkan pengalaman pengguna.</p>
                            <p>5.3. Kami tidak akan menjual atau membagikan data pribadi Anda kepada pihak ketiga tanpa persetujuan Anda.</p>
                            <p>5.4. Anda berhak untuk mengakses, memperbarui, atau menghapus data pribadi Anda.</p>
                        </div>
                    </section>

                    <!-- 6. Tanggung Jawab -->
                    <section class="mb-8">
                        <h2 class="mb-4 text-2xl font-semibold text-gray-900">6. Tanggung Jawab</h2>
                        <div class="space-y-3 text-gray-700">
                            <p>6.1. Platform ini disediakan "sebagaimana adanya" tanpa jaminan apapun.</p>
                            <p>6.2. Kami tidak bertanggung jawab atas kerugian yang timbul dari penggunaan platform ini.</p>
                            <p>6.3. Anda bertanggung jawab penuh atas keputusan yang Anda ambil berdasarkan informasi di platform.</p>
                            <p>6.4. Kami tidak menjamin ketersediaan platform secara terus-menerus.</p>
                        </div>
                    </section>

                    <!-- 7. Perubahan Ketentuan -->
                    <section class="mb-8">
                        <h2 class="mb-4 text-2xl font-semibold text-gray-900">7. Perubahan Ketentuan</h2>
                        <div class="space-y-3 text-gray-700">
                            <p>7.1. Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu.</p>
                            <p>7.2. Perubahan akan diberitahukan melalui platform atau email.</p>
                            <p>7.3. Penggunaan berkelanjutan setelah perubahan dianggap sebagai persetujuan terhadap ketentuan baru.</p>
                        </div>
                    </section>

                    <!-- 8. Penyelesaian Sengketa -->
                    <section class="mb-8">
                        <h2 class="mb-4 text-2xl font-semibold text-gray-900">8. Penyelesaian Sengketa</h2>
                        <div class="space-y-3 text-gray-700">
                            <p>8.1. Setiap sengketa akan diselesaikan melalui musyawarah dan mufakat.</p>
                            <p>8.2. Jika tidak tercapai kesepakatan, sengketa akan diselesaikan melalui pengadilan yang berwenang di Bengkalis, Riau.</p>
                            <p>8.3. Hukum yang berlaku adalah hukum Republik Indonesia.</p>
                        </div>
                    </section>

                    <!-- 9. Kontak -->
                    <section class="mb-8">
                        <h2 class="mb-4 text-2xl font-semibold text-gray-900">9. Informasi Kontak</h2>
                        <div class="p-6 bg-gray-50 rounded-lg">
                            <p class="mb-2 text-gray-700">Jika Anda memiliki pertanyaan mengenai syarat dan ketentuan ini, silakan hubungi kami:</p>
                            <div class="space-y-2">
                                <p><strong>Email:</strong> legal@adojobs.id</p>
                                <p><strong>Telepon:</strong> +62 812-3456-7890</p>
                                <p><strong>Alamat:</strong> Jl. Raya Bengkalis No. 123, Bengkalis, Riau 28711</p>
                            </div>
                        </div>
                    </section>

                    <!-- Footer -->
                    <div class="p-6 mt-12 text-center bg-indigo-50 rounded-lg">
                        <p class="font-medium text-indigo-800">
                            Dengan menggunakan platform {{ site_name() }}, Anda telah membaca, memahami, dan menyetujui syarat dan ketentuan ini.
                        </p>
                        <p class="mt-2 text-sm text-indigo-600">
                            Terima kasih telah mempercayai {{ site_name() }} sebagai partner karier Anda.
                        </p>
                    </div>

                    <!-- Back to Home -->
                    <div class="mt-8 text-center">
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
