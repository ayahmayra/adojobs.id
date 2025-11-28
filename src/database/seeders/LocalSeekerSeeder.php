<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seeker;
use Illuminate\Support\Facades\Hash;

class LocalSeekerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seekers = [
            [
                'name' => 'Siti Aminah',
                'email' => 'siti.aminah@example.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 15, Bengkalis',
                'bio' => 'Saya memiliki pengalaman 3 tahun sebagai asisten rumah tangga. Terampil dalam memasak, membersihkan rumah, dan merawat anak.',
                'skills' => 'Memasak, Membersihkan, Merawat Anak, Menjahit',
                'experience' => '3 tahun sebagai asisten rumah tangga',
                'education' => 'SMA',
                'resume_slug' => 'siti-aminah-resume',
            ],
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@example.com',
                'phone' => '081234567891',
                'address' => 'Jl. Sudirman No. 22, Bengkalis',
                'bio' => 'Saya memiliki pengalaman 5 tahun di bidang pertanian dan perkebunan. Terampil dalam menanam padi, sayuran, dan merawat tanaman.',
                'skills' => 'Pertanian, Perkebunan, Menanam, Merawat Tanaman',
                'experience' => '5 tahun di bidang pertanian',
                'education' => 'SMA',
                'resume_slug' => 'ahmad-rizki-resume',
            ],
            [
                'name' => 'Fatimah Sari',
                'email' => 'fatimah.sari@example.com',
                'phone' => '081234567892',
                'address' => 'Jl. Gatot Subroto No. 8, Bengkalis',
                'bio' => 'Saya memiliki pengalaman 2 tahun sebagai admin kantor. Terampil dalam mengetik, mengelola dokumen, dan melayani pelanggan.',
                'skills' => 'Administrasi, Mengetik, Microsoft Office, Melayani Pelanggan',
                'experience' => '2 tahun sebagai admin kantor',
                'education' => 'D3 Administrasi',
                'resume_slug' => 'fatimah-sari-resume',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '081234567893',
                'address' => 'Jl. Diponegoro No. 12, Bengkalis',
                'bio' => 'Saya memiliki pengalaman 4 tahun sebagai tukang kebun dan pemeliharaan. Terampil dalam merawat tanaman, membersihkan area, dan perawatan taman.',
                'skills' => 'Tukang Kebun, Pemeliharaan, Merawat Tanaman, Membersihkan',
                'experience' => '4 tahun sebagai tukang kebun',
                'education' => 'SMA',
                'resume_slug' => 'budi-santoso-resume',
            ],
            [
                'name' => 'Rina Wijaya',
                'email' => 'rina.wijaya@example.com',
                'phone' => '081234567894',
                'address' => 'Jl. Ahmad Yani No. 18, Bengkalis',
                'bio' => 'Saya memiliki pengalaman 3 tahun sebagai penjaga toko dan kasir. Terampil dalam melayani pelanggan, mengelola kas, dan menjaga keamanan toko.',
                'skills' => 'Penjaga Toko, Kasir, Melayani Pelanggan, Mengelola Kas',
                'experience' => '3 tahun sebagai penjaga toko',
                'education' => 'SMA',
                'resume_slug' => 'rina-wijaya-resume',
            ],
            [
                'name' => 'Muhammad Ali',
                'email' => 'muhammad.ali@example.com',
                'phone' => '081234567895',
                'address' => 'Jl. Imam Bonjol No. 25, Bengkalis',
                'bio' => 'Saya memiliki pengalaman 6 tahun sebagai sopir dan kurir. Terampil dalam mengemudi, navigasi, dan pengiriman barang.',
                'skills' => 'Mengemudi, Navigasi, Pengiriman, Melayani Pelanggan',
                'experience' => '6 tahun sebagai sopir dan kurir',
                'education' => 'SMA',
                'resume_slug' => 'muhammad-ali-resume',
            ],
            [
                'name' => 'Sari Dewi',
                'email' => 'sari.dewi@example.com',
                'phone' => '081234567896',
                'address' => 'Jl. Pahlawan No. 30, Bengkalis',
                'bio' => 'Saya memiliki pengalaman 2 tahun sebagai asisten dapur dan koki. Terampil dalam memasak, menyiapkan makanan, dan menjaga kebersihan dapur.',
                'skills' => 'Memasak, Asisten Dapur, Menyiapkan Makanan, Kebersihan',
                'experience' => '2 tahun sebagai asisten dapur',
                'education' => 'SMA',
                'resume_slug' => 'sari-dewi-resume',
            ],
            [
                'name' => 'Joko Susilo',
                'email' => 'joko.susilo@example.com',
                'phone' => '081234567897',
                'address' => 'Jl. Kartini No. 14, Bengkalis',
                'bio' => 'Saya memiliki pengalaman 5 tahun sebagai tukang bangunan dan konstruksi. Terampil dalam membangun, renovasi, dan perbaikan bangunan.',
                'skills' => 'Tukang Bangunan, Konstruksi, Renovasi, Perbaikan',
                'experience' => '5 tahun sebagai tukang bangunan',
                'education' => 'SMA',
                'resume_slug' => 'joko-susilo-resume',
            ],
            [
                'name' => 'Dewi Kartika',
                'email' => 'dewi.kartika@example.com',
                'phone' => '081234567898',
                'address' => 'Jl. Teuku Umar No. 20, Bengkalis',
                'bio' => 'Saya memiliki pengalaman 3 tahun sebagai perawat dan asisten medis. Terampil dalam merawat pasien, memberikan obat, dan membantu dokter.',
                'skills' => 'Perawat, Asisten Medis, Merawat Pasien, Memberikan Obat',
                'experience' => '3 tahun sebagai perawat',
                'education' => 'D3 Keperawatan',
                'resume_slug' => 'dewi-kartika-resume',
            ],
            [
                'name' => 'Andi Pratama',
                'email' => 'andi.pratama@example.com',
                'phone' => '081234567899',
                'address' => 'Jl. Sisingamangaraja No. 35, Bengkalis',
                'bio' => 'Saya memiliki pengalaman 4 tahun sebagai satpam dan keamanan. Terampil dalam menjaga keamanan, patroli, dan melayani tamu.',
                'skills' => 'Satpam, Keamanan, Patroli, Melayani Tamu',
                'experience' => '4 tahun sebagai satpam',
                'education' => 'SMA',
                'resume_slug' => 'andi-pratama-resume',
            ],
        ];

        foreach ($seekers as $seekerData) {
            // Create or update user
            $user = User::updateOrCreate(
                ['email' => $seekerData['email']],
                [
                    'name' => $seekerData['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'seeker',
                    'phone' => $seekerData['phone'],
                    'address' => $seekerData['address'],
                    'resume_slug' => $seekerData['resume_slug'],
                    'email_verified_at' => now(),
                ]
            );

            // Create or update seeker profile
            Seeker::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => $seekerData['bio'],
                    'skills' => $seekerData['skills'],
                    'experience' => $seekerData['experience'],
                    'education' => $seekerData['education'],
                ]
            );
        }
    }
}
