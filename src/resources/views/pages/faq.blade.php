<x-layouts.app>
    <x-slot name="title">FAQ - {{ site_name() }}</x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="px-4 py-8 mx-auto max-w-4xl text-center">
                <h1 class="text-4xl font-bold text-gray-900">Pertanyaan yang Sering Diajukan (FAQ)</h1>
                <p class="mt-4 text-xl text-gray-600">
                    Temukan jawaban untuk pertanyaan umum tentang {{ site_name() }}
                </p>
            </div>

            <!-- FAQ Content -->
            <div class="px-4 py-8 mx-auto max-w-4xl">
                <!-- Search Box -->
                <div class="mb-8">
                    <div class="relative">
                        <input type="text" 
                               id="faqSearch"
                               placeholder="Cari pertanyaan..." 
                               class="px-4 py-3 pl-12 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <svg class="absolute top-4 left-4 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Categories -->
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                    <button onclick="filterFAQ('all')" class="px-4 py-2 text-center text-white bg-indigo-600 rounded-lg transition-colors hover:bg-indigo-700">
                        Semua
                    </button>
                    <button onclick="filterFAQ('seeker')" class="px-4 py-2 text-center text-indigo-600 bg-indigo-50 rounded-lg transition-colors hover:bg-indigo-100">
                        Pencari Kerja
                    </button>
                    <button onclick="filterFAQ('employer')" class="px-4 py-2 text-center text-indigo-600 bg-indigo-50 rounded-lg transition-colors hover:bg-indigo-100">
                        Perusahaan
                    </button>
                </div>

                <!-- FAQ Items -->
                <div class="space-y-4" id="faqContainer">
                    
                    <!-- General Questions -->
                    <div class="p-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg">
                        <div class="p-6 bg-white rounded-lg">
                            <h2 class="mb-4 text-2xl font-semibold text-gray-900">Pertanyaan Umum</h2>
                            
                            <!-- Q1 -->
                            <div class="mb-4 faq-item" data-category="all">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Apa itu {{ site_name() }}?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>{{ site_name() }} adalah platform pencarian kerja yang menghubungkan pencari kerja dengan perusahaan di Pulau Bengkalis dan sekitarnya. Kami menyediakan lowongan kerja dari berbagai sektor, mulai dari pekerjaan formal hingga pekerjaan lokal seperti pertanian, kebersihan, dan asisten rumah tangga.</p>
                                </div>
                            </div>

                            <!-- Q2 -->
                            <div class="pt-4 mb-4 border-t faq-item" data-category="all">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Apakah {{ site_name() }} gratis?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Ya, {{ site_name() }} gratis untuk pencari kerja. Anda dapat mendaftar, mencari lowongan, dan melamar pekerjaan tanpa biaya apapun. Untuk perusahaan, kami menyediakan paket gratis untuk posting lowongan dengan fitur dasar.</p>
                                </div>
                            </div>

                            <!-- Q3 -->
                            <div class="pt-4 mb-4 border-t faq-item" data-category="all">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara mendaftar?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Klik tombol "Daftar" di halaman utama, pilih jenis akun (Pencari Kerja atau Perusahaan), lalu isi formulir registrasi dengan data yang valid. Setelah itu, verifikasi email Anda dan akun Anda siap digunakan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Seeker Questions -->
                    <div class="p-1 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg">
                        <div class="p-6 bg-white rounded-lg">
                            <h2 class="mb-4 text-2xl font-semibold text-gray-900">Untuk Pencari Kerja</h2>
                            
                            <!-- Q4 -->
                            <div class="mb-4 faq-item" data-category="seeker">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara mencari lowongan kerja?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Anda dapat mencari lowongan melalui halaman "Cari Lowongan" di menu utama. Gunakan filter berdasarkan kategori, lokasi, tipe pekerjaan, atau mode kerja untuk menemukan lowongan yang sesuai dengan keahlian dan minat Anda.</p>
                                </div>
                            </div>

                            <!-- Q5 -->
                            <div class="pt-4 mb-4 border-t faq-item" data-category="seeker">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara melamar pekerjaan?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Setelah menemukan lowongan yang menarik, klik "Lihat Detail" untuk membaca informasi lengkap. Jika tertarik, klik tombol "Lamar Sekarang" dan isi formulir lamaran dengan lengkap. Pastikan resume Anda sudah ter-update di profil.</p>
                                </div>
                            </div>

                            <!-- Q6 -->
                            <div class="pt-4 mb-4 border-t faq-item" data-category="seeker">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara membuat resume?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Setelah login, masuk ke Dashboard > Resume > Buat Resume. Isi semua bagian dengan lengkap: data pribadi, pendidikan, pengalaman kerja, keahlian, dan informasi lainnya. Resume Anda akan tersimpan dan dapat diakses oleh perusahaan yang tertarik.</p>
                                </div>
                            </div>

                            <!-- Q7 -->
                            <div class="pt-4 mb-4 border-t faq-item" data-category="seeker">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Apa itu fitur "Simpan Lowongan"?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Fitur "Simpan Lowongan" memungkinkan Anda menyimpan lowongan yang menarik untuk referensi nanti. Klik ikon hati pada lowongan untuk menyimpannya. Anda dapat melihat semua lowongan tersimpan di Dashboard > Lowongan Favorit.</p>
                                </div>
                            </div>

                            <!-- Q8 -->
                            <div class="pt-4 border-t faq-item" data-category="seeker">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara menghubungi perusahaan?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Anda dapat mengirim pesan langsung ke perusahaan melalui tombol "Kirim Pesan" di halaman detail lowongan. Sistem pesan kami memungkinkan komunikasi langsung antara pencari kerja dan perusahaan secara aman dan profesional.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employer Questions -->
                    <div class="p-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg">
                        <div class="p-6 bg-white rounded-lg">
                            <h2 class="mb-4 text-2xl font-semibold text-gray-900">Untuk Perusahaan</h2>
                            
                            <!-- Q9 -->
                            <div class="mb-4 faq-item" data-category="employer">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara memposting lowongan kerja?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Setelah login sebagai perusahaan, masuk ke Dashboard > Kelola Lowongan > Posting Lowongan Baru. Isi semua informasi lowongan dengan lengkap: judul posisi, deskripsi, kualifikasi, gaji, lokasi, dan detail lainnya. Lowongan akan langsung tayang setelah dipublikasikan.</p>
                                </div>
                            </div>

                            <!-- Q10 -->
                            <div class="pt-4 mb-4 border-t faq-item" data-category="employer">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara melihat lamaran yang masuk?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Masuk ke Dashboard > Kelola Lamaran untuk melihat semua lamaran yang masuk. Anda dapat memfilter berdasarkan lowongan, status lamaran, atau tanggal. Klik "Lihat Detail" untuk melihat informasi lengkap kandidat beserta resume mereka.</p>
                                </div>
                            </div>

                            <!-- Q11 -->
                            <div class="pt-4 mb-4 border-t faq-item" data-category="employer">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara mengubah atau menghapus lowongan?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Di Dashboard > Kelola Lowongan, pilih lowongan yang ingin diubah atau dihapus. Klik tombol "Edit" untuk mengubah informasi lowongan, atau "Hapus" untuk menghapus lowongan. Anda juga dapat mengubah status lowongan (Draft, Published, Closed, Filled).</p>
                                </div>
                            </div>

                            <!-- Q12 -->
                            <div class="pt-4 mb-4 border-t faq-item" data-category="employer">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Apa itu lowongan "Featured"?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Lowongan "Featured" adalah lowongan yang ditampilkan secara menonjol di halaman utama dan mendapat badge khusus. Lowongan ini mendapat eksposur lebih tinggi dan lebih mudah ditemukan oleh pencari kerja. Hubungi kami untuk informasi lebih lanjut tentang paket featured.</p>
                                </div>
                            </div>

                            <!-- Q13 -->
                            <div class="pt-4 border-t faq-item" data-category="employer">
                                <button onclick="toggleFAQ(this)" class="flex justify-between items-center w-full text-left">
                                    <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara menghubungi kandidat?</h3>
                                    <svg class="w-5 h-5 text-gray-500 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden mt-3 text-gray-600 faq-answer">
                                    <p>Anda dapat menghubungi kandidat melalui sistem pesan internal di Dashboard. Klik "Kirim Pesan" pada halaman detail kandidat untuk memulai percakapan. Semua komunikasi akan tersimpan dan dapat diakses kapan saja.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Contact Section -->
                <div class="p-6 mt-12 text-center bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg">
                    <h3 class="mb-2 text-2xl font-bold text-white">Tidak menemukan jawaban Anda?</h3>
                    <p class="mb-6 text-lg text-indigo-100">
                        Tim kami siap membantu Anda dengan pertanyaan apapun
                    </p>
                    <a href="{{ route('contact') }}" 
                       class="inline-flex items-center px-6 py-3 text-base font-medium text-indigo-600 bg-white rounded-lg transition-colors hover:bg-gray-50">
                        Hubungi Kami
                    </a>
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

    <!-- FAQ Interactive Script -->
    <script>
        function toggleFAQ(button) {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('svg');
            
            answer.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        function filterFAQ(category) {
            const items = document.querySelectorAll('.faq-item');
            const buttons = document.querySelectorAll('[onclick^="filterFAQ"]');
            
            // Update button styles
            buttons.forEach(btn => {
                if (btn.getAttribute('onclick').includes(category)) {
                    btn.className = 'px-4 py-2 text-center text-white bg-indigo-600 rounded-lg transition-colors hover:bg-indigo-700';
                } else {
                    btn.className = 'px-4 py-2 text-center text-indigo-600 bg-indigo-50 rounded-lg transition-colors hover:bg-indigo-100';
                }
            });
            
            // Filter items
            items.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                if (category === 'all' || itemCategory === category || itemCategory === 'all') {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Search functionality
        document.getElementById('faqSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.faq-item');
            
            items.forEach(item => {
                const question = item.querySelector('h3').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</x-layouts.app>
