<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user as author
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->warn('No admin user found. Please create an admin user first.');
            return;
        }

        $articles = [
            [
                'title' => 'Panduan Lengkap Mencari Pekerjaan di AdoJobs.id',
                'excerpt' => 'Pelajari cara menggunakan platform AdoJobs.id untuk mencari pekerjaan impian Anda. Dari membuat profil hingga melamar pekerjaan.',
                'content' => '<p>AdoJobs.id adalah platform terbaik untuk mencari pekerjaan yang sesuai dengan keahlian dan minat Anda. Berikut panduan lengkap untuk memaksimalkan penggunaan platform ini:</p>

<h3>1. Membuat Profil yang Menarik</h3>
<p>Langkah pertama adalah membuat profil yang menarik dan lengkap:</p>
<ul>
<li><strong>Informasi Pribadi</strong>: Isi data diri dengan lengkap dan akurat</li>
<li><strong>Foto Profil</strong>: Gunakan foto profesional yang jelas</li>
<li><strong>Ringkasan</strong>: Tulis ringkasan singkat tentang diri Anda dan keahlian</li>
<li><strong>Pengalaman Kerja</strong>: Cantumkan pengalaman kerja yang relevan</li>
<li><strong>Pendidikan</strong>: Masukkan riwayat pendidikan terbaru</li>
<li><strong>Keahlian</strong>: Daftar keahlian yang Anda miliki</li>
</ul>

<h3>2. Membuat Resume yang Menarik</h3>
<p>Resume adalah kunci utama untuk menarik perhatian recruiter:</p>
<ul>
<li><strong>Format yang Jelas</strong>: Gunakan format yang mudah dibaca</li>
<li><strong>Konten yang Relevan</strong>: Fokus pada pengalaman yang relevan dengan posisi yang dilamar</li>
<li><strong>Kata Kunci</strong>: Gunakan kata kunci yang sesuai dengan industri</li>
<li><strong>Update Berkala</strong>: Selalu update resume dengan pengalaman terbaru</li>
</ul>

<h3>3. Mencari Lowongan Kerja</h3>
<p>Gunakan fitur pencarian yang tersedia:</p>
<ul>
<li><strong>Filter Kategori</strong>: Pilih kategori pekerjaan yang sesuai</li>
<li><strong>Filter Lokasi</strong>: Tentukan lokasi kerja yang diinginkan</li>
<li><strong>Filter Gaji</strong>: Sesuaikan dengan ekspektasi gaji</li>
<li><strong>Kata Kunci</strong>: Gunakan kata kunci spesifik untuk pencarian yang lebih tepat</li>
</ul>

<h3>4. Melamar Pekerjaan</h3>
<p>Saat melamar pekerjaan:</p>
<ul>
<li><strong>Baca Deskripsi</strong>: Pahami dengan baik deskripsi pekerjaan</li>
<li><strong>Sesuaikan Resume</strong>: Sesuaikan resume dengan kebutuhan posisi</li>
<li><strong>Cover Letter</strong>: Tulis cover letter yang menarik</li>
<li><strong>Follow Up</strong>: Jangan ragu untuk follow up setelah melamar</li>
</ul>

<h3>5. Tips Sukses</h3>
<ul>
<li><strong>Aktif di Platform</strong>: Login secara rutin dan update profil</li>
<li><strong>Networking</strong>: Manfaatkan fitur networking untuk membangun koneksi</li>
<li><strong>Belajar Terus</strong>: Ikuti perkembangan industri dan tingkatkan keahlian</li>
<li><strong>Bersabar</strong>: Proses pencarian kerja membutuhkan waktu dan kesabaran</li>
</ul>

<p>Dengan mengikuti panduan ini, Anda akan memiliki peluang yang lebih besar untuk mendapatkan pekerjaan impian melalui AdoJobs.id.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Cara Membuat Lowongan Kerja yang Menarik untuk Perusahaan',
                'excerpt' => 'Pelajari cara membuat lowongan kerja yang menarik dan efektif untuk menarik kandidat terbaik di AdoJobs.id.',
                'content' => '<p>Sebagai perusahaan, membuat lowongan kerja yang menarik adalah kunci untuk mendapatkan kandidat terbaik. Berikut panduan lengkap untuk membuat lowongan kerja yang efektif:</p>

<h3>1. Judul Posisi yang Jelas</h3>
<p>Judul posisi harus jelas dan spesifik:</p>
<ul>
<li><strong>Spesifik</strong>: Hindari judul yang terlalu umum</li>
<li><strong>Jelas</strong>: Gunakan istilah yang mudah dipahami</li>
<li><strong>Menarik</strong>: Buat judul yang menarik perhatian</li>
<li><strong>Relevan</strong>: Sesuaikan dengan industri dan level posisi</li>
</ul>

<h3>2. Deskripsi Pekerjaan yang Komprehensif</h3>
<p>Deskripsi pekerjaan harus mencakup:</p>
<ul>
<li><strong>Ringkasan Posisi</strong>: Jelaskan peran dan tanggung jawab utama</li>
<li><strong>Kualifikasi</strong>: Cantumkan kualifikasi yang dibutuhkan</li>
<li><strong>Keahlian</strong>: Daftar keahlian teknis dan soft skills</li>
<li><strong>Pengalaman</strong>: Tentukan level pengalaman yang dibutuhkan</li>
<li><strong>Pendidikan</strong>: Spesifikasi tingkat pendidikan yang diinginkan</li>
</ul>

<h3>3. Tanggung Jawab dan Tugas</h3>
<p>Jelaskan tanggung jawab dengan detail:</p>
<ul>
<li><strong>Tugas Harian</strong>: Daftar tugas yang akan dilakukan setiap hari</li>
<li><strong>Proyek</strong>: Jelaskan proyek yang akan dikerjakan</li>
<li><strong>Kolaborasi</strong>: Deskripsikan interaksi dengan tim dan departemen lain</li>
<li><strong>Target</strong>: Cantumkan target dan KPI yang harus dicapai</li>
</ul>

<h3>4. Kualifikasi dan Persyaratan</h3>
<p>Tentukan kualifikasi yang dibutuhkan:</p>
<ul>
<li><strong>Keahlian Teknis</strong>: Spesifikasi keahlian teknis yang wajib</li>
<li><strong>Soft Skills</strong>: Cantumkan soft skills yang penting</li>
<li><strong>Pengalaman</strong>: Tentukan minimal pengalaman kerja</li>
<li><strong>Pendidikan</strong>: Spesifikasi tingkat pendidikan</li>
<li><strong>Sertifikasi</strong>: Cantumkan sertifikasi yang dibutuhkan</li>
</ul>

<h3>5. Benefit dan Kompensasi</h3>
<p>Jelaskan benefit yang ditawarkan:</p>
<ul>
<li><strong>Gaji</strong>: Range gaji yang ditawarkan</li>
<li><strong>Benefit</strong>: Asuransi, cuti, bonus, dll</li>
<li><strong>Perkembangan</strong>: Peluang pengembangan karir</li>
<li><strong>Lingkungan Kerja</strong>: Deskripsikan budaya perusahaan</li>
</ul>

<h3>6. Proses Rekrutmen</h3>
<p>Jelaskan proses rekrutmen:</p>
<ul>
<li><strong>Tahapan</strong>: Daftar tahapan seleksi</li>
<li><strong>Timeline</strong>: Perkiraan waktu proses</li>
<li><strong>Kontak</strong>: Informasi kontak untuk pertanyaan</li>
<li><strong>Dokumen</strong>: Dokumen yang dibutuhkan</li>
</ul>

<h3>7. Tips Tambahan</h3>
<ul>
<li><strong>Gunakan Bahasa yang Jelas</strong>: Hindari jargon yang sulit dipahami</li>
<li><strong>Update Berkala</strong>: Update lowongan secara berkala</li>
<li><strong>Responsif</strong>: Balas aplikasi dengan cepat</li>
<li><strong>Feedback</strong>: Berikan feedback kepada kandidat</li>
</ul>

<p>Dengan mengikuti panduan ini, Anda akan dapat membuat lowongan kerja yang menarik dan mendapatkan kandidat terbaik untuk perusahaan Anda.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Tips Sukses Interview Kerja Online',
                'excerpt' => 'Pelajari tips dan trik untuk sukses dalam interview kerja online, dari persiapan teknis hingga etika interview.',
                'content' => '<p>Interview kerja online menjadi hal yang umum di era digital ini. Berikut tips lengkap untuk sukses dalam interview online:</p>

<h3>1. Persiapan Teknis</h3>
<p>Pastikan peralatan dan koneksi internet Anda siap:</p>
<ul>
<li><strong>Koneksi Internet</strong>: Pastikan koneksi internet stabil dan cepat</li>
<li><strong>Perangkat</strong>: Gunakan laptop atau komputer dengan kamera dan mikrofon yang baik</li>
<li><strong>Software</strong>: Install dan test aplikasi video call yang akan digunakan</li>
<li><strong>Backup Plan</strong>: Siapkan rencana cadangan jika terjadi masalah teknis</li>
</ul>

<h3>2. Setting Lokasi</h3>
<p>Pilih lokasi yang tepat untuk interview:</p>
<ul>
<li><strong>Pencahayaan</strong>: Pastikan pencahayaan yang cukup dan natural</li>
<li><strong>Background</strong>: Gunakan background yang bersih dan profesional</li>
<li><strong>Suara</strong>: Pilih lokasi yang tenang dan bebas gangguan</li>
<li><strong>Posisi</strong>: Duduk dengan posisi yang nyaman dan profesional</li>
</ul>

<h3>3. Persiapan Diri</h3>
<p>Persiapkan diri secara fisik dan mental:</p>
<ul>
<li><strong>Pakaian</strong>: Gunakan pakaian yang sesuai dengan budaya perusahaan</li>
<li><strong>Grooming</strong>: Pastikan penampilan rapi dan profesional</li>
<li><strong>Dokumen</strong>: Siapkan CV, portfolio, dan dokumen pendukung</li>
<li><strong>Catatan</strong>: Buat catatan tentang perusahaan dan posisi yang dilamar</li>
</ul>

<h3>4. Etika Interview Online</h3>
<p>Ikuti etika yang baik selama interview:</p>
<ul>
<li><strong>Tepat Waktu</strong>: Masuk meeting 5-10 menit lebih awal</li>
<li><strong>Eye Contact</strong>: Lihat kamera, bukan layar</li>
<li><strong>Body Language</strong>: Pertahankan postur yang baik</li>
<li><strong>Mendengarkan</strong>: Fokus dan dengarkan dengan baik</li>
<li><strong>Tidak Multitasking</strong>: Hindari aktivitas lain selama interview</li>
</ul>

<h3>5. Komunikasi yang Efektif</h3>
<p>Gunakan komunikasi yang jelas dan efektif:</p>
<ul>
<li><strong>Bicara Jelas</strong>: Bicara dengan volume dan kecepatan yang tepat</li>
<li><strong>Pause</strong>: Berikan jeda untuk interviewer berbicara</li>
<li><strong>Pertanyaan</strong>: Ajukan pertanyaan yang relevan dan menunjukkan minat</li>
<li><strong>Jawaban</strong>: Berikan jawaban yang terstruktur dan relevan</li>
</ul>

<h3>6. Persiapan Pertanyaan</h3>
<p>Siapkan pertanyaan yang akan diajukan:</p>
<ul>
<li><strong>Tentang Posisi</strong>: Tanyakan detail tentang tanggung jawab dan ekspektasi</li>
<li><strong>Tentang Perusahaan</strong>: Pelajari tentang perusahaan dan ajukan pertanyaan yang relevan</li>
<li><strong>Tentang Tim</strong>: Tanyakan tentang tim dan budaya kerja</li>
<li><strong>Tentang Perkembangan</strong>: Tanyakan tentang peluang pengembangan karir</li>
</ul>

<h3>7. Follow Up</h3>
<p>Lakukan follow up setelah interview:</p>
<ul>
<li><strong>Thank You</strong>: Kirim email terima kasih dalam 24 jam</li>
<li><strong>Pertanyaan Tambahan</strong>: Ajukan pertanyaan yang belum sempat ditanyakan</li>
<li><strong>Timeline</strong>: Tanyakan timeline keputusan</li>
<li><strong>Kontak</strong>: Pastikan Anda memiliki kontak untuk follow up</li>
</ul>

<h3>8. Tips Tambahan</h3>
<ul>
<li><strong>Practice</strong>: Berlatih dengan teman atau keluarga</li>
<li><strong>Recording</strong>: Rekam diri sendiri untuk evaluasi</li>
<li><strong>Research</strong>: Pelajari perusahaan dan interviewer</li>
<li><strong>Confidence</strong>: Tampilkan kepercayaan diri yang positif</li>
</ul>

<p>Dengan mengikuti tips ini, Anda akan siap menghadapi interview online dengan percaya diri dan profesional.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Cara Membangun Personal Branding untuk Karier',
                'excerpt' => 'Pelajari strategi membangun personal branding yang kuat untuk meningkatkan karier dan peluang kerja.',
                'content' => '<p>Personal branding adalah cara Anda memposisikan diri di mata orang lain, terutama di dunia profesional. Berikut panduan lengkap untuk membangun personal branding yang kuat:</p>

<h3>1. Definisikan Diri Anda</h3>
<p>Langkah pertama adalah memahami siapa Anda:</p>
<ul>
<li><strong>Values</strong>: Tentukan nilai-nilai yang Anda pegang</li>
<li><strong>Passion</strong>: Identifikasi passion dan minat Anda</li>
<li><strong>Keahlian</strong>: Daftar keahlian yang Anda miliki</li>
<li><strong>Unique Selling Point</strong>: Temukan keunikan yang membedakan Anda</li>
<li><strong>Target Audience</strong>: Tentukan siapa yang ingin Anda jangkau</li>
</ul>

<h3>2. Konsistensi di Semua Platform</h3>
<p>Pastikan konsistensi di semua platform digital:</p>
<ul>
<li><strong>LinkedIn</strong>: Optimalkan profil LinkedIn dengan informasi lengkap</li>
<li><strong>Website</strong>: Buat website pribadi atau portfolio online</li>
<li><strong>Social Media</strong>: Gunakan social media dengan bijak dan profesional</li>
<li><strong>AdoJobs.id</strong>: Lengkapi profil di platform AdoJobs.id</li>
<li><strong>Konten</strong>: Buat konten yang konsisten dengan personal brand</li>
</ul>

<h3>3. Konten yang Berkualitas</h3>
<p>Buat konten yang menambah nilai:</p>
<ul>
<li><strong>Blog</strong>: Tulis artikel tentang keahlian dan pengalaman Anda</li>
<li><strong>Video</strong>: Buat video tutorial atau sharing pengalaman</li>
<li><strong>Podcast</strong>: Berpartisipasi dalam podcast atau buat podcast sendiri</li>
<li><strong>Speaking</strong>: Berbicara di event atau webinar</li>
<li><strong>Mentoring</strong>: Berikan mentoring kepada junior</li>
</ul>

<h3>4. Networking yang Efektif</h3>
<p>Bangun jaringan yang kuat:</p>
<ul>
<li><strong>Event</strong>: Hadiri event dan konferensi industri</li>
<li><strong>Online</strong>: Aktif di grup dan forum online</li>
<li><strong>Mentorship</strong>: Cari mentor dan menjadi mentor</li>
<li><strong>Collaboration</strong>: Berkolaborasi dengan profesional lain</li>
<li><strong>Follow Up</strong>: Jaga hubungan dengan kontak yang sudah ada</li>
</ul>

<h3>5. Online Presence</h3>
<p>Kuatkan kehadiran online:</p>
<ul>
<li><strong>SEO</strong>: Optimalkan profil untuk pencarian online</li>
<li><strong>Konten Regular</strong>: Post konten secara konsisten</li>
<li><strong>Engagement</strong>: Aktif berinteraksi dengan audiens</li>
<li><strong>Professional Photos</strong>: Gunakan foto profesional di semua platform</li>
<li><strong>Bio</strong>: Tulis bio yang menarik dan informatif</li>
</ul>

<h3>6. Thought Leadership</h3>
<p>Posisikan diri sebagai thought leader:</p>
<ul>
<li><strong>Expertise</strong>: Tunjukkan keahlian di bidang tertentu</li>
<li><strong>Opinions</strong>: Berikan opini yang beralasan dan konstruktif</li>
<li><strong>Innovation</strong>: Berikan ide-ide inovatif</li>
<li><strong>Trends</strong>: Ikuti dan diskusikan tren terbaru</li>
<li><strong>Research</strong>: Lakukan riset dan share hasilnya</li>
</ul>

<h3>7. Authenticity</h3>
<p>Tetap autentik dalam personal branding:</p>
<ul>
<li><strong>True Self</strong>: Tampilkan diri yang sebenarnya</li>
<li><strong>Honesty</strong>: Jujur tentang keahlian dan pengalaman</li>
<li><strong>Transparency</strong>: Transparan dalam komunikasi</li>
<li><strong>Consistency</strong>: Konsisten dengan nilai-nilai yang dianut</li>
<li><strong>Growth</strong>: Tunjukkan proses belajar dan berkembang</li>
</ul>

<h3>8. Measurement dan Improvement</h3>
<p>Ukur dan tingkatkan personal brand:</p>
<ul>
<li><strong>Metrics</strong>: Ukur engagement dan reach</li>
<li><strong>Feedback</strong>: Minta feedback dari kolega dan mentor</li>
<li><strong>Adjustment</strong>: Sesuaikan strategi berdasarkan hasil</li>
<li><strong>Learning</strong>: Terus belajar dan mengembangkan diri</li>
<li><strong>Evolution</strong>: Biarkan personal brand berkembang seiring waktu</li>
</ul>

<h3>9. Tips Praktis</h3>
<ul>
<li><strong>Start Small</strong>: Mulai dengan platform yang Anda kuasai</li>
<li><strong>Be Patient</strong>: Personal branding membutuhkan waktu</li>
<li><strong>Stay Updated</strong>: Ikuti perkembangan tren dan teknologi</li>
<li><strong>Be Helpful</strong>: Fokus pada memberikan nilai kepada orang lain</li>
<li><strong>Stay Professional</strong>: Selalu menjaga profesionalisme</li>
</ul>

<p>Dengan membangun personal branding yang kuat, Anda akan memiliki keunggulan kompetitif di dunia kerja dan dapat menarik peluang yang lebih baik.</p>',
                'status' => 'published',
                'published_at' => now()->subHours(6),
            ],
            [
                'title' => 'Panduan Menggunakan Fitur Messaging di AdoJobs.id',
                'excerpt' => 'Pelajari cara menggunakan fitur messaging untuk berkomunikasi dengan recruiter dan kandidat secara efektif.',
                'content' => '<p>Fitur messaging di AdoJobs.id memungkinkan komunikasi langsung antara recruiter dan kandidat. Berikut panduan lengkap untuk menggunakan fitur ini:</p>

<h3>1. Memulai Percakapan</h3>
<p>Cara memulai percakapan dengan recruiter atau kandidat:</p>
<ul>
<li><strong>Dari Profil</strong>: Klik tombol "Kirim Pesan" di profil user</li>
<li><strong>Dari Lowongan</strong>: Gunakan fitur "Hubungi Perusahaan" di halaman lowongan</li>
<li><strong>Dari Dashboard</strong>: Akses menu "Pesan" di dashboard</li>
<li><strong>Admin Contact</strong>: Gunakan fitur "Hubungi Admin" untuk bantuan</li>
</ul>

<h3>2. Etika Komunikasi</h3>
<p>Ikuti etika komunikasi yang baik:</p>
<ul>
<li><strong>Salam</strong>: Mulai dengan salam yang sopan</li>
<li><strong>Perkenalan</strong>: Perkenalkan diri dengan singkat</li>
<li><strong>Tujuan</strong>: Jelaskan tujuan komunikasi dengan jelas</li>
<li><strong>Profesional</strong>: Gunakan bahasa yang profesional</li>
<li><strong>Respectful</strong>: Hormati waktu dan privasi lawan bicara</li>
</ul>

<h3>3. Tips untuk Kandidat</h3>
<p>Tips berkomunikasi sebagai kandidat:</p>
<ul>
<li><strong>Pertanyaan Relevan</strong>: Ajukan pertanyaan yang relevan dengan posisi</li>
<li><strong>Show Interest</strong>: Tunjukkan minat yang tulus terhadap perusahaan</li>
<li><strong>Be Specific</strong>: Ajukan pertanyaan yang spesifik dan detail</li>
<li><strong>Follow Up</strong>: Lakukan follow up dengan sopan</li>
<li><strong>Thank You</strong>: Ucapkan terima kasih setelah mendapat respons</li>
</ul>

<h3>4. Tips untuk Recruiter</h3>
<p>Tips berkomunikasi sebagai recruiter:</p>
<ul>
<li><strong>Response Time</strong>: Balas pesan dengan cepat</li>
<li><strong>Clear Information</strong>: Berikan informasi yang jelas dan lengkap</li>
<li><strong>Professional</strong>: Pertahankan profesionalisme dalam komunikasi</li>
<li><strong>Helpful</strong>: Berikan bantuan dan informasi yang berguna</li>
<li><strong>Follow Up</strong>: Lakukan follow up dengan kandidat yang potensial</li>
</ul>

<h3>5. Struktur Pesan yang Baik</h3>
<p>Buat pesan yang terstruktur dan mudah dipahami:</p>
<ul>
<li><strong>Subject</strong>: Gunakan subject yang jelas (jika ada)</li>
<li><strong>Greeting</strong>: Mulai dengan salam yang sesuai</li>
<li><strong>Introduction</strong>: Perkenalkan diri dengan singkat</li>
<li><strong>Main Content</strong>: Sampaikan pesan utama dengan jelas</li>
<li><strong>Call to Action</strong>: Akhiri dengan ajakan atau pertanyaan yang jelas</li>
<li><strong>Closing</strong>: Tutup dengan salam yang sopan</li>
</ul>

<h3>6. Menangani Pertanyaan Umum</h3>
<p>Siapkan jawaban untuk pertanyaan yang sering diajukan:</p>
<p><strong>Untuk Kandidat:</strong></p>
<ul>
<li>Informasi tentang perusahaan</li>
<li>Detail posisi yang dibutuhkan</li>
<li>Proses rekrutmen</li>
<li>Benefit dan kompensasi</li>
<li>Timeline keputusan</li>
</ul>
<p><strong>Untuk Recruiter:</strong></p>
<ul>
<li>Kualifikasi dan pengalaman</li>
<li>Ketersediaan untuk interview</li>
<li>Ekspektasi gaji</li>
<li>Pertanyaan tentang posisi</li>
<li>Timeline keputusan</li>
</ul>

<h3>7. Follow Up yang Efektif</h3>
<p>Lakukan follow up yang tepat waktu:</p>
<ul>
<li><strong>Timing</strong>: Lakukan follow up dalam 1-2 minggu</li>
<li><strong>Content</strong>: Sampaikan informasi tambahan yang relevan</li>
<li><strong>Tone</strong>: Gunakan tone yang sopan dan profesional</li>
<li><strong>Purpose</strong>: Jelaskan tujuan follow up dengan jelas</li>
<li><strong>Next Steps</strong>: Sampaikan langkah selanjutnya</li>
</ul>

<h3>8. Menangani Respons Negatif</h3>
<p>Cara menangani respons yang tidak diinginkan:</p>
<ul>
<li><strong>Stay Professional</strong>: Tetap profesional dalam respons</li>
<li><strong>Learn</strong>: Ambil pelajaran dari feedback</li>
<li><strong>Move On</strong>: Fokus pada peluang lain</li>
<li><strong>Improve</strong>: Gunakan feedback untuk perbaikan</li>
<li><strong>Stay Positive</strong>: Pertahankan sikap positif</li>
</ul>

<h3>9. Tips Tambahan</h3>
<ul>
<li><strong>Be Patient</strong>: Berikan waktu yang cukup untuk respons</li>
<li><strong>Be Clear</strong>: Sampaikan pesan dengan jelas dan tidak ambigu</li>
<li><strong>Be Respectful</strong>: Hormati privasi dan waktu lawan bicara</li>
<li><strong>Be Persistent</strong>: Lakukan follow up yang konsisten tapi tidak mengganggu</li>
<li><strong>Be Grateful</strong>: Ucapkan terima kasih untuk setiap respons</li>
</ul>

<h3>10. Best Practices</h3>
<ul>
<li><strong>Quick Response</strong>: Balas pesan dalam 24 jam</li>
<li><strong>Professional Language</strong>: Gunakan bahasa yang profesional</li>
<li><strong>Clear Communication</strong>: Sampaikan pesan dengan jelas</li>
<li><strong>Respectful Tone</strong>: Gunakan tone yang sopan dan menghormati</li>
<li><strong>Helpful Content</strong>: Berikan informasi yang berguna</li>
</ul>

<p>Dengan mengikuti panduan ini, Anda akan dapat menggunakan fitur messaging di AdoJobs.id secara efektif untuk membangun komunikasi yang baik dengan recruiter atau kandidat.</p>',
                'status' => 'published',
                'published_at' => now()->subHours(2),
            ],
            [
                'title' => 'Cara Membuat Resume yang Menarik untuk Recruiter',
                'excerpt' => 'Pelajari cara membuat resume yang menarik dan efektif untuk menarik perhatian recruiter di AdoJobs.id.',
                'content' => '<p>Resume adalah kunci utama untuk menarik perhatian recruiter. Berikut panduan lengkap untuk membuat resume yang menarik dan efektif:</p>

<h3>1. Struktur Resume yang Baik</h3>
<p>Organisir resume dengan struktur yang jelas:</p>
<ul>
<li><strong>Header</strong>: Nama, kontak, dan informasi dasar</li>
<li><strong>Summary</strong>: Ringkasan singkat tentang diri Anda</li>
<li><strong>Experience</strong>: Pengalaman kerja yang relevan</li>
<li><strong>Education</strong>: Riwayat pendidikan</li>
<li><strong>Skills</strong>: Keahlian yang dimiliki</li>
<li><strong>Achievements</strong>: Prestasi dan pencapaian</li>
</ul>

<h3>2. Header yang Profesional</h3>
<p>Buat header yang menarik perhatian:</p>
<ul>
<li><strong>Nama</strong>: Gunakan font yang jelas dan mudah dibaca</li>
<li><strong>Kontak</strong>: Sertakan email, telepon, dan LinkedIn</li>
<li><strong>Lokasi</strong>: Cantumkan lokasi (kota, negara)</li>
<li><strong>Website</strong>: Sertakan website atau portfolio (jika ada)</li>
<li><strong>Photo</strong>: Gunakan foto profesional (opsional)</li>
</ul>

<h3>3. Summary yang Menarik</h3>
<p>Tulis ringkasan yang menggambarkan diri Anda:</p>
<ul>
<li><strong>Professional Title</strong>: Cantumkan posisi yang diinginkan</li>
<li><strong>Years of Experience</strong>: Sebutkan pengalaman kerja</li>
<li><strong>Key Skills</strong>: Daftar 3-5 keahlian utama</li>
<li><strong>Value Proposition</strong>: Jelaskan nilai yang Anda bawa</li>
<li><strong>Career Goal</strong>: Sampaikan tujuan karir</li>
</ul>

<h3>4. Pengalaman Kerja yang Relevan</h3>
<p>Sajikan pengalaman kerja dengan efektif:</p>
<ul>
<li><strong>Chronological Order</strong>: Urutkan dari yang terbaru</li>
<li><strong>Job Title</strong>: Cantumkan posisi dengan jelas</li>
<li><strong>Company</strong>: Nama perusahaan dan industri</li>
<li><strong>Duration</strong>: Periode kerja</li>
<li><strong>Responsibilities</strong>: Tanggung jawab utama</li>
<li><strong>Achievements</strong>: Pencapaian yang diukur dengan angka</li>
</ul>

<h3>5. Format yang Mudah Dibaca</h3>
<p>Gunakan format yang mudah dipahami:</p>
<ul>
<li><strong>Font</strong>: Gunakan font yang profesional (Arial, Calibri, Times New Roman)</li>
<li><strong>Size</strong>: Ukuran font 10-12 untuk konten, 14-16 untuk header</li>
<li><strong>Spacing</strong>: Berikan jarak yang cukup antar bagian</li>
<li><strong>Bullets</strong>: Gunakan bullet points untuk daftar</li>
<li><strong>Length</strong>: Maksimal 2 halaman untuk fresh graduate, 3 halaman untuk senior</li>
</ul>

<h3>6. Kata Kunci yang Relevan</h3>
<p>Gunakan kata kunci yang sesuai dengan industri:</p>
<ul>
<li><strong>Industry Terms</strong>: Gunakan terminologi industri</li>
<li><strong>Skills</strong>: Cantumkan keahlian yang dicari recruiter</li>
<li><strong>Technologies</strong>: Sebutkan teknologi yang dikuasai</li>
<li><strong>Certifications</strong>: Cantumkan sertifikasi yang relevan</li>
<li><strong>Keywords</strong>: Sesuaikan dengan job description</li>
</ul>

<h3>7. Achievement yang Terukur</h3>
<p>Sertakan pencapaian yang dapat diukur:</p>
<ul>
<li><strong>Numbers</strong>: Gunakan angka untuk menunjukkan hasil</li>
<li><strong>Percentages</strong>: Cantumkan peningkatan dalam persentase</li>
<li><strong>Revenue</strong>: Sebutkan kontribusi terhadap revenue</li>
<li><strong>Efficiency</strong>: Jelaskan peningkatan efisiensi</li>
<li><strong>Awards</strong>: Cantumkan penghargaan yang diterima</li>
</ul>

<h3>8. Skills yang Relevan</h3>
<p>Daftar keahlian yang sesuai dengan posisi:</p>
<ul>
<li><strong>Technical Skills</strong>: Keahlian teknis yang dibutuhkan</li>
<li><strong>Soft Skills</strong>: Kemampuan interpersonal</li>
<li><strong>Languages</strong>: Bahasa yang dikuasai</li>
<li><strong>Software</strong>: Software yang bisa digunakan</li>
<li><strong>Tools</strong>: Tools yang dikuasai</li>
</ul>

<h3>9. Education yang Relevan</h3>
<p>Cantumkan pendidikan yang relevan:</p>
<ul>
<li><strong>Degree</strong>: Gelar dan jurusan</li>
<li><strong>Institution</strong>: Nama universitas</li>
<li><strong>Year</strong>: Tahun lulus</li>
<li><strong>GPA</strong>: IPK (jika bagus)</li>
<li><strong>Relevant Courses</strong>: Mata kuliah yang relevan</li>
</ul>

<h3>10. Tips Tambahan</h3>
<ul>
<li><strong>Customize</strong>: Sesuaikan resume dengan setiap posisi</li>
<li><strong>Proofread</strong>: Periksa grammar dan typo</li>
<li><strong>Update</strong>: Update resume secara berkala</li>
<li><strong>Format</strong>: Gunakan format yang konsisten</li>
<li><strong>Save</strong>: Simpan dalam format PDF</li>
</ul>

<h3>11. Common Mistakes to Avoid</h3>
<p>Hindari kesalahan umum:</p>
<ul>
<li><strong>Too Long</strong>: Resume yang terlalu panjang</li>
<li><strong>Generic</strong>: Resume yang terlalu umum</li>
<li><strong>Typos</strong>: Kesalahan penulisan</li>
<li><strong>Outdated</strong>: Informasi yang sudah tidak relevan</li>
<li><strong>Unprofessional</strong>: Email atau kontak yang tidak profesional</li>
</ul>

<h3>12. ATS Optimization</h3>
<p>Optimalkan untuk ATS (Applicant Tracking System):</p>
<ul>
<li><strong>Keywords</strong>: Gunakan kata kunci yang relevan</li>
<li><strong>Format</strong>: Gunakan format yang ATS-friendly</li>
<li><strong>Sections</strong>: Gunakan section headers yang jelas</li>
<li><strong>File Format</strong>: Simpan dalam format yang kompatibel</li>
<li><strong>No Graphics</strong>: Hindari grafik atau gambar yang kompleks</li>
</ul>

<p>Dengan mengikuti panduan ini, Anda akan dapat membuat resume yang menarik dan efektif untuk menarik perhatian recruiter di AdoJobs.id.</p>',
                'status' => 'published',
                'published_at' => now()->subHours(1),
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
                'author_id' => $admin->id,
                'meta_data' => [
                    'title' => $articleData['title'],
                    'description' => $articleData['excerpt'],
                ],
            ]);
        }

        $this->command->info('Articles seeded successfully!');
    }
}
