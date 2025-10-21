<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\Category;
use App\Models\Employer;
use App\Models\User;

class LocalJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $pertanian = Category::where('slug', 'pertanian-perkebunan')->first();
        $kebersihan = Category::where('slug', 'kebersihan-pemeliharaan')->first();
        $asisten = Category::where('slug', 'asisten-rumah-tangga')->first();
        $admin = Category::where('slug', 'administrasi-kantor')->first();
        $perdagangan = Category::where('slug', 'perdagangan-jasa')->first();
        $konstruksi = Category::where('slug', 'konstruksi-bangunan')->first();
        $transportasi = Category::where('slug', 'transportasi-logistik')->first();
        $kuliner = Category::where('slug', 'kuliner-makanan')->first();
        $kesehatan = Category::where('slug', 'kesehatan-perawatan')->first();
        $pendidikan = Category::where('slug', 'pendidikan-pelatihan')->first();
        $keamanan = Category::where('slug', 'keamanan-satpam')->first();

        // Get employers
        $hajiRahman = Employer::where('company_name', 'Kebun Sawit Haji Rahman')->first();
        $warungSiti = Employer::where('company_name', 'Warung Makan Siti')->first();
        $tokoFauzi = Employer::where('company_name', 'Toko Bangunan Fauzi')->first();
        $rsBengkalis = Employer::where('company_name', 'Rumah Sakit Bengkalis')->first();
        $cvJaya = Employer::where('company_name', 'CV Jaya Abadi')->first();
        $tokoRina = Employer::where('company_name', 'Toko Sembako Rina')->first();
        $bengkelAli = Employer::where('company_name', 'Bengkel Ali Motor')->first();
        $klinikSari = Employer::where('company_name', 'Klinik Sari Sehat')->first();
        $cvSantoso = Employer::where('company_name', 'CV Santoso Konstruksi')->first();
        $sdKartika = Employer::where('company_name', 'Sekolah Dasar Kartika')->first();

        $jobs = [
            [
                'title' => 'Pekerja Kebun Sawit',
                'description' => 'Dibutuhkan pekerja untuk kebun sawit. Tugas: menanam, merawat, dan memanen sawit. Pengalaman di bidang pertanian lebih diutamakan.',
                'requirements' => 'SMA, pengalaman di bidang pertanian, sehat jasmani, siap bekerja di lapangan',
                'benefits' => 'Gaji sesuai UMR, tunjangan kesehatan, bonus panen',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 2500000,
                'salary_max' => 3500000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $hajiRahman->id,
                'category_id' => $pertanian->id,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Asisten Dapur',
                'description' => 'Dibutuhkan asisten dapur untuk warung makan. Tugas: membantu memasak, menyiapkan bahan, menjaga kebersihan dapur.',
                'requirements' => 'SMA, bisa memasak, sehat, rajin, jujur',
                'benefits' => 'Gaji sesuai UMR, makan siang, bonus bulanan',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 2000000,
                'salary_max' => 2800000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $warungSiti->id,
                'category_id' => $kuliner->id,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Kasir Toko Bangunan',
                'description' => 'Dibutuhkan kasir untuk toko material bangunan. Tugas: melayani pelanggan, mengelola kas, menjaga keamanan toko.',
                'requirements' => 'SMA, bisa menghitung, jujur, ramah, pengalaman sebagai kasir lebih diutamakan',
                'benefits' => 'Gaji sesuai UMR, tunjangan transport, bonus penjualan',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 2200000,
                'salary_max' => 3000000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $tokoFauzi->id,
                'category_id' => $perdagangan->id,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Perawat Rumah Sakit',
                'description' => 'Dibutuhkan perawat untuk rumah sakit. Tugas: merawat pasien, memberikan obat, membantu dokter, menjaga kebersihan ruangan.',
                'requirements' => 'D3 Keperawatan, memiliki STR, sehat jasmani, sabar, teliti',
                'benefits' => 'Gaji sesuai UMR, tunjangan kesehatan, tunjangan transport, cuti tahunan',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 3000000,
                'salary_max' => 4000000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $rsBengkalis->id,
                'category_id' => $kesehatan->id,
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Petugas Kebersihan',
                'description' => 'Dibutuhkan petugas kebersihan untuk gedung perkantoran. Tugas: membersihkan lantai, toilet, ruangan, dan area umum.',
                'requirements' => 'SMA, sehat jasmani, rajin, jujur, bisa bekerja shift',
                'benefits' => 'Gaji sesuai UMR, tunjangan shift, seragam, alat kerja',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 2000000,
                'salary_max' => 2500000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $cvJaya->id,
                'category_id' => $kebersihan->id,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Penjaga Toko Sembako',
                'description' => 'Dibutuhkan penjaga toko untuk toko sembako. Tugas: melayani pelanggan, mengatur barang, menjaga keamanan toko, membantu pembukuan.',
                'requirements' => 'SMA, jujur, ramah, bisa menghitung, pengalaman di toko lebih diutamakan',
                'benefits' => 'Gaji sesuai UMR, tunjangan transport, bonus penjualan',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 2000000,
                'salary_max' => 2800000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $tokoRina->id,
                'category_id' => $perdagangan->id,
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Mekanik Motor',
                'description' => 'Dibutuhkan mekanik motor untuk bengkel. Tugas: memperbaiki motor, mengganti spare part, melayani pelanggan, menjaga kebersihan bengkel.',
                'requirements' => 'SMA, pengalaman sebagai mekanik, bisa memperbaiki motor, jujur, teliti',
                'benefits' => 'Gaji sesuai UMR, tunjangan transport, bonus perbaikan, alat kerja',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 2500000,
                'salary_max' => 3500000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $bengkelAli->id,
                'category_id' => $perdagangan->id,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Asisten Medis Klinik',
                'description' => 'Dibutuhkan asisten medis untuk klinik. Tugas: membantu dokter, merawat pasien, memberikan obat, menjaga kebersihan klinik.',
                'requirements' => 'D3 Keperawatan, memiliki STR, sehat jasmani, sabar, teliti',
                'benefits' => 'Gaji sesuai UMR, tunjangan kesehatan, tunjangan transport',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 2500000,
                'salary_max' => 3200000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $klinikSari->id,
                'category_id' => $kesehatan->id,
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'Tukang Bangunan',
                'description' => 'Dibutuhkan tukang bangunan untuk proyek konstruksi. Tugas: membangun, renovasi, perbaikan bangunan, mengikuti instruksi mandor.',
                'requirements' => 'SMA, pengalaman sebagai tukang bangunan, sehat jasmani, bisa bekerja tim',
                'benefits' => 'Gaji sesuai UMR, tunjangan transport, alat kerja, bonus proyek',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 2500000,
                'salary_max' => 3500000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $cvSantoso->id,
                'category_id' => $konstruksi->id,
                'published_at' => now()->subDays(9),
            ],
            [
                'title' => 'Guru SD',
                'description' => 'Dibutuhkan guru untuk sekolah dasar. Tugas: mengajar siswa, membuat rencana pembelajaran, menilai hasil belajar, berkomunikasi dengan orang tua.',
                'requirements' => 'S1 Pendidikan, memiliki sertifikat mengajar, sabar, kreatif, bisa mengajar dengan baik',
                'benefits' => 'Gaji sesuai UMR, tunjangan transport, cuti tahunan, bonus kinerja',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 3000000,
                'salary_max' => 4000000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $sdKartika->id,
                'category_id' => $pendidikan->id,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Satpam Rumah Sakit',
                'description' => 'Dibutuhkan satpam untuk rumah sakit. Tugas: menjaga keamanan, patroli, melayani tamu, membantu pasien dan keluarga.',
                'requirements' => 'SMA, sehat jasmani, jujur, disiplin, bisa bekerja shift',
                'benefits' => 'Gaji sesuai UMR, tunjangan shift, seragam, alat keamanan',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 2200000,
                'salary_max' => 2800000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $rsBengkalis->id,
                'category_id' => $keamanan->id,
                'published_at' => now()->subDays(11),
            ],
            [
                'title' => 'Sopir Pengiriman',
                'description' => 'Dibutuhkan sopir untuk pengiriman barang. Tugas: mengemudi, mengirim barang, melayani pelanggan, menjaga keamanan barang.',
                'requirements' => 'SMA, memiliki SIM C, pengalaman mengemudi, sehat jasmani, jujur',
                'benefits' => 'Gaji sesuai UMR, tunjangan transport, bonus pengiriman, asuransi',
                'location' => 'Bengkalis, Riau',
                'salary_min' => 2500000,
                'salary_max' => 3200000,
                'employment_type' => 'full-time',
                'status' => 'published',
                'employer_id' => $tokoFauzi->id,
                'category_id' => $transportasi->id,
                'published_at' => now()->subDays(12),
            ],
        ];

        foreach ($jobs as $jobData) {
            // Remove employment_type and add job_type
            $jobData['job_type'] = $jobData['employment_type'];
            unset($jobData['employment_type']);
            
            // Add required fields
            $jobData['city'] = 'Bengkalis';
            $jobData['state'] = 'Riau';
            $jobData['country'] = 'Indonesia';
            $jobData['salary_currency'] = 'IDR';
            $jobData['salary_period'] = 'monthly';
            $jobData['experience_level'] = 'entry';
            $jobData['education_level'] = 'high_school';
            $jobData['vacancies'] = 1;
            $jobData['application_deadline'] = now()->addDays(30);
            
            Job::create($jobData);
        }
    }
}
