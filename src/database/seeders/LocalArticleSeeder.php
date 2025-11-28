<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocalArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user as author (if available)
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->warn('No admin user found. Articles will be created without author.');
        }

        $articles = [
            [
                'title' => 'Peluang Kerja Lokal di Bengkalis: Dari Pertanian hingga Jasa',
                'excerpt' => 'Temukan berbagai peluang kerja lokal di Bengkalis, mulai dari sektor pertanian, perkebunan, hingga jasa dan perdagangan yang berkembang pesat.',
                'content' => '<p>Bengkalis sebagai salah satu kabupaten di Riau memiliki potensi ekonomi yang besar dengan berbagai sektor yang berkembang. Platform AdoJobs.id hadir untuk menjembatani pencari kerja dengan peluang kerja lokal yang tersedia.</p>

<h3>1. Sektor Pertanian dan Perkebunan</h3>
<p>Bengkalis dikenal dengan sektor pertanian dan perkebunan yang maju, terutama kelapa sawit dan karet. Peluang kerja di sektor ini meliputi:</p>
<ul>
<li><strong>Pekerja Kebun</strong>: Menanam, merawat, dan memanen tanaman</li>
<li><strong>Operator Alat Berat</strong>: Mengoperasikan traktor dan alat pertanian</li>
<li><strong>Mandor Kebun</strong>: Mengawasi dan mengkoordinasikan pekerja kebun</li>
<li><strong>Teknisi Irigasi</strong>: Merawat dan memperbaiki sistem irigasi</li>
</ul>

<h3>2. Sektor Jasa dan Perdagangan</h3>
<p>Perkembangan ekonomi Bengkalis juga ditandai dengan tumbuhnya sektor jasa dan perdagangan:</p>
<ul>
<li><strong>Penjaga Toko</strong>: Melayani pelanggan di toko sembako dan kebutuhan sehari-hari</li>
<li><strong>Kasir</strong>: Mengelola transaksi dan keuangan toko</li>
<li><strong>Sales</strong>: Menjual produk dan membangun relasi dengan pelanggan</li>
<li><strong>Driver</strong>: Mengantar barang dan jasa pengiriman</li>
</ul>

<h3>3. Sektor Konstruksi dan Bangunan</h3>
<p>Pembangunan infrastruktur di Bengkalis membuka peluang kerja di sektor konstruksi:</p>
<ul>
<li><strong>Tukang Bangunan</strong>: Membangun dan merenovasi bangunan</li>
<li><strong>Mandor Proyek</strong>: Mengawasi dan mengkoordinasikan proyek konstruksi</li>
<li><strong>Operator Alat Berat</strong>: Mengoperasikan excavator, bulldozer, dan alat konstruksi</li>
<li><strong>Teknisi Listrik</strong>: Instalasi dan perawatan sistem kelistrikan</li>
</ul>

<h3>4. Sektor Kesehatan dan Pendidikan</h3>
<p>Kebutuhan akan layanan kesehatan dan pendidikan terus meningkat:</p>
<ul>
<li><strong>Perawat</strong>: Merawat pasien di rumah sakit dan klinik</li>
<li><strong>Guru</strong>: Mengajar di sekolah dasar dan menengah</li>
<li><strong>Asisten Medis</strong>: Membantu dokter dan perawat di klinik</li>
<li><strong>Satpam</strong>: Menjaga keamanan di berbagai instansi</li>
</ul>

<h3>5. Tips Mencari Kerja Lokal di Bengkalis</h3>
<p>Berikut tips untuk meningkatkan peluang mendapatkan kerja lokal:</p>
<ul>
<li><strong>Kenali Potensi Lokal</strong>: Pahami sektor ekonomi yang berkembang di Bengkalis</li>
<li><strong>Kembangkan Keahlian</strong>: Asah kemampuan yang dibutuhkan di sektor tersebut</li>
<li><strong>Bangun Jaringan</strong>: Perluas relasi dengan masyarakat lokal</li>
<li><strong>Gunakan Platform Digital</strong>: Manfaatkan AdoJobs.id untuk mencari peluang kerja</li>
</ul>

<p>Dengan memahami potensi ekonomi lokal dan mengembangkan keahlian yang sesuai, Anda dapat menemukan peluang kerja yang tepat di Bengkalis.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Kiat Sukses Bekerja di Sektor Pertanian Bengkalis',
                'excerpt' => 'Pelajari kiat-kiat sukses bekerja di sektor pertanian dan perkebunan di Bengkalis, mulai dari persiapan hingga pengembangan karir.',
                'content' => '<p>Sektor pertanian dan perkebunan merupakan tulang punggung ekonomi Bengkalis. Dengan luas lahan yang besar dan iklim yang mendukung, sektor ini menawarkan banyak peluang kerja yang menjanjikan.</p>

<h3>1. Jenis Pekerjaan di Sektor Pertanian</h3>
<p>Berbagai jenis pekerjaan tersedia di sektor pertanian Bengkalis:</p>
<ul>
<li><strong>Pekerja Kebun Sawit</strong>: Menanam, merawat, dan memanen kelapa sawit</li>
<li><strong>Pekerja Kebun Karet</strong>: Merawat pohon karet dan mengumpulkan getah</li>
<li><strong>Pekerja Kebun Sayuran</strong>: Menanam dan merawat sayuran organik</li>
<li><strong>Pekerja Kebun Buah</strong>: Merawat pohon buah-buahan dan memanen hasil</li>
</ul>

<h3>2. Keahlian yang Dibutuhkan</h3>
<p>Untuk sukses di sektor pertanian, Anda perlu mengembangkan keahlian berikut:</p>
<ul>
<li><strong>Pengetahuan Tanaman</strong>: Memahami jenis tanaman dan cara merawatnya</li>
<li><strong>Kemampuan Fisik</strong>: Siap bekerja di lapangan dengan kondisi cuaca apapun</li>
<li><strong>Keterampilan Alat</strong>: Bisa menggunakan alat pertanian tradisional dan modern</li>
<li><strong>Kerja Tim</strong>: Bisa bekerja sama dengan tim untuk mencapai target</li>
</ul>

<h3>3. Peluang Pengembangan Karir</h3>
<p>Dari pekerja kebun, Anda bisa berkembang menjadi:</p>
<ul>
<li><strong>Mandor Kebun</strong>: Mengawasi dan mengkoordinasikan pekerja</li>
<li><strong>Supervisor</strong>: Mengelola beberapa kebun sekaligus</li>
<li><strong>Manajer Kebun</strong>: Bertanggung jawab atas operasional kebun</li>
<li><strong>Konsultan Pertanian</strong>: Memberikan saran dan solusi pertanian</li>
</ul>

<h3>4. Tips Sukses Bekerja di Pertanian</h3>
<p>Berikut tips untuk sukses di sektor pertanian:</p>
<ul>
<li><strong>Rajin dan Disiplin</strong>: Konsisten dalam menjalankan tugas</li>
<li><strong>Belajar Terus</strong>: Update pengetahuan tentang teknik pertanian terbaru</li>
<li><strong>Jaga Kesehatan</strong>: Istirahat yang cukup dan makan makanan bergizi</li>
<li><strong>Bangun Relasi</strong>: Jalin hubungan baik dengan rekan kerja dan atasan</li>
</ul>

<h3>5. Manfaat Bekerja di Sektor Pertanian</h3>
<p>Bekerja di sektor pertanian memiliki banyak manfaat:</p>
<ul>
<li><strong>Gaji Stabil</strong>: Penghasilan yang konsisten setiap bulan</li>
<li><strong>Bonus Panen</strong>: Tambahan penghasilan saat panen raya</li>
<li><strong>Pengalaman Berharga</strong>: Keahlian yang bisa dikembangkan</li>
<li><strong>Kontribusi Sosial</strong>: Berkontribusi pada ketahanan pangan</li>
</ul>

<p>Dengan dedikasi dan kerja keras, sektor pertanian di Bengkalis menawarkan peluang karir yang menjanjikan dan berkelanjutan.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Membangun Bisnis Lokal di Bengkalis: Peluang dan Tantangan',
                'excerpt' => 'Jelajahi peluang bisnis lokal di Bengkalis dan tantangan yang dihadapi, serta tips untuk memulai usaha yang sukses.',
                'content' => '<p>Bengkalis menawarkan berbagai peluang bisnis lokal yang menjanjikan. Dari sektor pertanian hingga jasa, banyak peluang yang bisa dimanfaatkan untuk membangun usaha yang sukses.</p>

<h3>1. Peluang Bisnis di Sektor Pertanian</h3>
<p>Sektor pertanian di Bengkalis menawarkan berbagai peluang bisnis:</p>
<ul>
<li><strong>Budidaya Kelapa Sawit</strong>: Menanam dan mengelola kebun sawit</li>
<li><strong>Budidaya Karet</strong>: Mengembangkan perkebunan karet</li>
<li><strong>Budidaya Sayuran</strong>: Menanam sayuran organik untuk pasar lokal</li>
<li><strong>Budidaya Ikan</strong>: Mengembangkan tambak ikan air tawar</li>
</ul>

<h3>2. Peluang Bisnis di Sektor Jasa</h3>
<p>Sektor jasa juga berkembang pesat di Bengkalis:</p>
<ul>
<li><strong>Jasa Transportasi</strong>: Menyediakan layanan angkutan barang dan penumpang</li>
<li><strong>Jasa Konstruksi</strong>: Membangun dan merenovasi bangunan</li>
<li><strong>Jasa Kebersihan</strong>: Menyediakan layanan kebersihan untuk kantor dan rumah</li>
<li><strong>Jasa Perbaikan</strong>: Memperbaiki alat elektronik dan kendaraan</li>
</ul>

<h3>3. Peluang Bisnis di Sektor Perdagangan</h3>
<p>Perdagangan lokal juga menawarkan peluang yang besar:</p>
<ul>
<li><strong>Toko Sembako</strong>: Menyediakan kebutuhan sehari-hari masyarakat</li>
<li><strong>Toko Bangunan</strong>: Menjual material bangunan dan alat konstruksi</li>
<li><strong>Toko Elektronik</strong>: Menjual dan memperbaiki alat elektronik</li>
<li><strong>Toko Pakaian</strong>: Menyediakan pakaian dan aksesoris</li>
</ul>

<h3>4. Tantangan dalam Berbisnis Lokal</h3>
<p>Berbagai tantangan yang dihadapi dalam berbisnis lokal:</p>
<ul>
<li><strong>Akses Modal</strong>: Kesulitan mendapatkan modal untuk memulai usaha</li>
<li><strong>Pasar Terbatas</strong>: Pasar lokal yang terbatas dibandingkan pasar nasional</li>
<li><strong>Kompetisi</strong>: Persaingan dengan bisnis yang sudah established</li>
<li><strong>Infrastruktur</strong>: Keterbatasan infrastruktur yang mendukung bisnis</li>
</ul>

<h3>5. Tips Sukses Berbisnis Lokal</h3>
<p>Berikut tips untuk sukses dalam berbisnis lokal:</p>
<ul>
<li><strong>Kenali Pasar</strong>: Pahami kebutuhan dan preferensi masyarakat lokal</li>
<li><strong>Buat Produk Berkualitas</strong>: Fokus pada kualitas produk dan layanan</li>
<li><strong>Bangun Relasi</strong>: Jalin hubungan baik dengan pelanggan dan supplier</li>
<li><strong>Gunakan Teknologi</strong>: Manfaatkan platform digital untuk promosi dan penjualan</li>
</ul>

<h3>6. Manfaat Berbisnis Lokal</h3>
<p>Berbisnis lokal memiliki banyak manfaat:</p>
<ul>
<li><strong>Kontribusi Sosial</strong>: Berkontribusi pada perekonomian lokal</li>
<li><strong>Peluang Kerja</strong>: Menciptakan lapangan kerja untuk masyarakat lokal</li>
<li><strong>Pengembangan Wilayah</strong>: Membantu mengembangkan infrastruktur lokal</li>
<li><strong>Keuntungan Finansial</strong>: Mendapatkan keuntungan yang berkelanjutan</li>
</ul>

<p>Dengan strategi yang tepat dan dedikasi yang tinggi, bisnis lokal di Bengkalis dapat berkembang menjadi usaha yang sukses dan berkelanjutan.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Keterampilan yang Dibutuhkan untuk Kerja Lokal di Bengkalis',
                'excerpt' => 'Pelajari keterampilan yang dibutuhkan untuk berbagai jenis pekerjaan lokal di Bengkalis dan cara mengembangkannya.',
                'content' => '<p>Setiap jenis pekerjaan lokal di Bengkalis membutuhkan keterampilan yang spesifik. Dengan memahami keterampilan yang dibutuhkan, Anda dapat mempersiapkan diri untuk peluang kerja yang tersedia.</p>

<h3>1. Keterampilan untuk Sektor Pertanian</h3>
<p>Untuk bekerja di sektor pertanian, Anda perlu mengembangkan keterampilan berikut:</p>
<ul>
<li><strong>Pengetahuan Tanaman</strong>: Memahami jenis tanaman dan cara merawatnya</li>
<li><strong>Kemampuan Fisik</strong>: Siap bekerja di lapangan dengan kondisi cuaca apapun</li>
<li><strong>Keterampilan Alat</strong>: Bisa menggunakan alat pertanian tradisional dan modern</li>
<li><strong>Kerja Tim</strong>: Bisa bekerja sama dengan tim untuk mencapai target</li>
</ul>

<h3>2. Keterampilan untuk Sektor Jasa</h3>
<p>Sektor jasa membutuhkan keterampilan yang berbeda:</p>
<ul>
<li><strong>Komunikasi</strong>: Bisa berkomunikasi dengan baik dengan pelanggan</li>
<li><strong>Pelayanan</strong>: Memberikan pelayanan yang memuaskan</li>
<li><strong>Problem Solving</strong>: Mampu menyelesaikan masalah dengan cepat</li>
<li><strong>Time Management</strong>: Mengelola waktu dengan efisien</li>
</ul>

<h3>3. Keterampilan untuk Sektor Konstruksi</h3>
<p>Untuk bekerja di sektor konstruksi, Anda perlu keterampilan:</p>
<ul>
<li><strong>Keterampilan Teknis</strong>: Memahami teknik konstruksi dan bangunan</li>
<li><strong>Kemampuan Fisik</strong>: Siap bekerja dengan beban berat</li>
<li><strong>Keselamatan Kerja</strong>: Memahami prosedur keselamatan kerja</li>
<li><strong>Kerja Tim</strong>: Bisa bekerja sama dalam tim konstruksi</li>
</ul>

<h3>4. Keterampilan untuk Sektor Perdagangan</h3>
<p>Sektor perdagangan membutuhkan keterampilan:</p>
<ul>
<li><strong>Penjualan</strong>: Mampu menjual produk dengan efektif</li>
<li><strong>Komunikasi</strong>: Berkomunikasi dengan pelanggan dan supplier</li>
<li><strong>Manajemen</strong>: Mengelola inventori dan keuangan</li>
<li><strong>Marketing</strong>: Memahami strategi pemasaran lokal</li>
</ul>

<h3>5. Cara Mengembangkan Keterampilan</h3>
<p>Berikut cara mengembangkan keterampilan yang dibutuhkan:</p>
<ul>
<li><strong>Pelatihan</strong>: Ikuti pelatihan dan kursus yang relevan</li>
<li><strong>Praktik</strong>: Praktikkan keterampilan dalam situasi nyata</li>
<li><strong>Mentoring</strong>: Belajar dari orang yang berpengalaman</li>
<li><strong>Networking</strong>: Bangun jaringan dengan profesional di bidang yang sama</li>
</ul>

<h3>6. Keterampilan Soft Skills yang Penting</h3>
<p>Selain keterampilan teknis, soft skills juga sangat penting:</p>
<ul>
<li><strong>Komunikasi</strong>: Bisa berkomunikasi dengan baik</li>
<li><strong>Kerja Tim</strong>: Bisa bekerja sama dalam tim</li>
<li><strong>Adaptabilitas</strong>: Mampu beradaptasi dengan perubahan</li>
<li><strong>Inisiatif</strong>: Mampu mengambil inisiatif dan bertanggung jawab</li>
</ul>

<p>Dengan mengembangkan keterampilan yang tepat, Anda dapat meningkatkan peluang mendapatkan pekerjaan yang sesuai dengan minat dan kemampuan di Bengkalis.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Mengoptimalkan AdoJobs.id untuk Pencarian Kerja Lokal',
                'excerpt' => 'Pelajari cara menggunakan AdoJobs.id secara efektif untuk mencari pekerjaan lokal di Bengkalis dan sekitarnya.',
                'content' => '<p>AdoJobs.id adalah platform yang dirancang khusus untuk mempertemukan pencari kerja dengan peluang kerja lokal di Bengkalis. Berikut panduan lengkap untuk mengoptimalkan penggunaan platform ini.</p>

<h3>1. Membuat Profil yang Menarik</h3>
<p>Profil yang menarik adalah kunci utama untuk menarik perhatian recruiter:</p>
<ul>
<li><strong>Foto Profil</strong>: Gunakan foto yang jelas dan profesional</li>
<li><strong>Informasi Lengkap</strong>: Isi semua informasi yang diminta dengan akurat</li>
<li><strong>Bio yang Menarik</strong>: Tulis bio yang menggambarkan keahlian dan pengalaman Anda</li>
<li><strong>Kontak yang Aktif</strong>: Pastikan nomor telepon dan email yang aktif</li>
</ul>

<h3>2. Menggunakan Fitur Pencarian</h3>
<p>Manfaatkan fitur pencarian untuk menemukan pekerjaan yang sesuai:</p>
<ul>
<li><strong>Filter Kategori</strong>: Gunakan filter kategori untuk menyaring pekerjaan</li>
<li><strong>Filter Lokasi</strong>: Pilih lokasi yang sesuai dengan domisili Anda</li>
<li><strong>Filter Gaji</strong>: Sesuaikan dengan ekspektasi gaji Anda</li>
<li><strong>Filter Jenis Kerja</strong>: Pilih jenis kerja yang sesuai (full-time, part-time, dll)</li>
</ul>

<h3>3. Membuat Resume yang Menarik</h3>
<p>Resume yang menarik akan meningkatkan peluang Anda:</p>
<ul>
<li><strong>Format yang Jelas</strong>: Gunakan format yang mudah dibaca</li>
<li><strong>Konten yang Relevan</strong>: Fokus pada pengalaman yang relevan</li>
<li><strong>Kata Kunci</strong>: Gunakan kata kunci yang sesuai dengan industri</li>
<li><strong>Update Berkala</strong>: Selalu update resume dengan pengalaman terbaru</li>
</ul>

<h3>4. Melamar Pekerjaan</h3>
<p>Ketika melamar pekerjaan, perhatikan hal-hal berikut:</p>
<ul>
<li><strong>Baca Deskripsi</strong>: Baca deskripsi pekerjaan dengan teliti</li>
<li><strong>Sesuaikan Resume</strong>: Sesuaikan resume dengan kebutuhan pekerjaan</li>
<li><strong>Kirim Lamaran</strong>: Kirim lamaran dengan cepat setelah lowongan dipublikasikan</li>
<li><strong>Follow Up</strong>: Follow up lamaran Anda jika diperlukan</li>
</ul>

<h3>5. Membangun Jaringan</h3>
<p>Jaringan yang kuat akan membantu Anda mendapatkan pekerjaan:</p>
<ul>
<li><strong>Koneksi</strong>: Bangun koneksi dengan profesional di bidang yang sama</li>
<li><strong>Referensi</strong>: Minta referensi dari orang yang Anda kenal</li>
<li><strong>Networking</strong>: Ikuti acara networking dan komunitas profesional</li>
<li><strong>Online Presence</strong>: Jaga kehadiran online yang profesional</li>
</ul>

<h3>6. Tips Sukses di AdoJobs.id</h3>
<p>Berikut tips untuk sukses menggunakan AdoJobs.id:</p>
<ul>
<li><strong>Konsisten</strong>: Konsisten dalam mengupdate profil dan melamar pekerjaan</li>
<li><strong>Responsif</strong>: Responsif terhadap pesan dan notifikasi</li>
<li><strong>Profesional</strong>: Selalu bersikap profesional dalam berkomunikasi</li>
<li><strong>Belajar</strong>: Terus belajar dan mengembangkan keterampilan</li>
</ul>

<p>Dengan mengoptimalkan penggunaan AdoJobs.id, Anda dapat meningkatkan peluang mendapatkan pekerjaan yang sesuai dengan minat dan kemampuan di Bengkalis.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(1),
            ],
        ];

        foreach ($articles as $articleData) {
            Article::create([
                'title' => $articleData['title'],
                'slug' => Article::generateSlug($articleData['title']),
                'excerpt' => $articleData['excerpt'],
                'content' => $articleData['content'],
                'status' => $articleData['status'],
                'published_at' => $articleData['published_at'],
                'author_id' => $admin ? $admin->id : null,  // Nullable if no admin
                'meta_data' => [
                    'title' => $articleData['title'],
                    'description' => $articleData['excerpt'],
                ],
            ]);
        }
    }
}
