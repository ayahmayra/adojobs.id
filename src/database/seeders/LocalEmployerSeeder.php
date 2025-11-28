<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employer;
use Illuminate\Support\Facades\Hash;

class LocalEmployerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employers = [
            [
                'name' => 'Bapak Haji Rahman',
                'email' => 'haji.rahman@example.com',
                'phone' => '081234567900',
                'address' => 'Jl. Merdeka No. 50, Bengkalis',
                'company_name' => 'Kebun Sawit Haji Rahman',
                'company_description' => 'Perkebunan sawit keluarga yang telah beroperasi selama 20 tahun di Bengkalis',
                'company_address' => 'Jl. Merdeka No. 50, Bengkalis',
                'company_phone' => '081234567900',
                'company_website' => 'https://kebun-sawit-rahman.com',
                'is_verified' => true,
            ],
            [
                'name' => 'Ibu Siti Nurhaliza',
                'email' => 'siti.nurhaliza@example.com',
                'phone' => '081234567901',
                'address' => 'Jl. Sudirman No. 75, Bengkalis',
                'company_name' => 'Warung Makan Siti',
                'company_description' => 'Warung makan keluarga yang menyajikan masakan khas Melayu dan Indonesia',
                'company_address' => 'Jl. Sudirman No. 75, Bengkalis',
                'company_phone' => '081234567901',
                'company_website' => null,
                'is_verified' => false,
            ],
            [
                'name' => 'Bapak Ahmad Fauzi',
                'email' => 'ahmad.fauzi@example.com',
                'phone' => '081234567902',
                'address' => 'Jl. Gatot Subroto No. 30, Bengkalis',
                'company_name' => 'Toko Bangunan Fauzi',
                'company_description' => 'Toko material bangunan dan alat konstruksi terlengkap di Bengkalis',
                'company_address' => 'Jl. Gatot Subroto No. 30, Bengkalis',
                'company_phone' => '081234567902',
                'company_website' => null,
                'is_verified' => false,
            ],
            [
                'name' => 'Ibu Fatimah Zahra',
                'email' => 'fatimah.zahra@example.com',
                'phone' => '081234567903',
                'address' => 'Jl. Diponegoro No. 45, Bengkalis',
                'company_name' => 'Rumah Sakit Bengkalis',
                'company_description' => 'Rumah sakit umum yang melayani masyarakat Bengkalis dan sekitarnya',
                'company_address' => 'Jl. Diponegoro No. 45, Bengkalis',
                'company_phone' => '081234567903',
                'company_website' => 'https://rs-bengkalis.com',
                'is_verified' => true,
            ],
            [
                'name' => 'Bapak Joko Widodo',
                'email' => 'joko.widodo@example.com',
                'phone' => '081234567904',
                'address' => 'Jl. Ahmad Yani No. 60, Bengkalis',
                'company_name' => 'CV Jaya Abadi',
                'company_description' => 'Perusahaan jasa kebersihan dan pemeliharaan gedung',
                'company_address' => 'Jl. Ahmad Yani No. 60, Bengkalis',
                'company_phone' => '081234567904',
                'company_website' => null,
                'is_verified' => false,
            ],
            [
                'name' => 'Ibu Rina Sari',
                'email' => 'rina.sari@example.com',
                'phone' => '081234567905',
                'address' => 'Jl. Imam Bonjol No. 80, Bengkalis',
                'company_name' => 'Toko Sembako Rina',
                'company_description' => 'Toko sembako dan kebutuhan sehari-hari keluarga',
                'company_address' => 'Jl. Imam Bonjol No. 80, Bengkalis',
                'company_phone' => '081234567905',
                'company_website' => null,
                'is_verified' => false,
            ],
            [
                'name' => 'Bapak Muhammad Ali',
                'email' => 'muhammad.ali@example.com',
                'phone' => '081234567906',
                'address' => 'Jl. Pahlawan No. 90, Bengkalis',
                'company_name' => 'Bengkel Ali Motor',
                'company_description' => 'Bengkel motor dan sepeda motor terlengkap di Bengkalis',
                'company_address' => 'Jl. Pahlawan No. 90, Bengkalis',
                'company_phone' => '081234567906',
                'company_website' => null,
                'is_verified' => false,
            ],
            [
                'name' => 'Ibu Sari Dewi',
                'email' => 'sari.dewi@example.com',
                'phone' => '081234567907',
                'address' => 'Jl. Kartini No. 100, Bengkalis',
                'company_name' => 'Klinik Sari Sehat',
                'company_description' => 'Klinik kesehatan keluarga yang melayani masyarakat Bengkalis',
                'company_address' => 'Jl. Kartini No. 100, Bengkalis',
                'company_phone' => '081234567907',
                'company_website' => null,
                'is_verified' => false,
            ],
            [
                'name' => 'Bapak Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '081234567908',
                'address' => 'Jl. Teuku Umar No. 110, Bengkalis',
                'company_name' => 'CV Santoso Konstruksi',
                'company_description' => 'Perusahaan konstruksi dan renovasi bangunan',
                'company_address' => 'Jl. Teuku Umar No. 110, Bengkalis',
                'company_phone' => '081234567908',
                'company_website' => null,
                'is_verified' => false,
            ],
            [
                'name' => 'Ibu Dewi Kartika',
                'email' => 'dewi.kartika@example.com',
                'phone' => '081234567909',
                'address' => 'Jl. Sisingamangaraja No. 120, Bengkalis',
                'company_name' => 'Sekolah Dasar Kartika',
                'company_description' => 'Sekolah dasar swasta yang berkualitas di Bengkalis',
                'company_address' => 'Jl. Sisingamangaraja No. 120, Bengkalis',
                'company_phone' => '081234567909',
                'company_website' => null,
                'is_verified' => false,
            ],
        ];

        foreach ($employers as $employerData) {
            // Create or update user
            $user = User::updateOrCreate(
                ['email' => $employerData['email']],
                [
                    'name' => $employerData['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'employer',
                    'phone' => $employerData['phone'],
                    'address' => $employerData['address'],
                    'email_verified_at' => now(),
                ]
            );

            // Create or update employer profile
            Employer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $employerData['company_name'],
                    'company_description' => $employerData['company_description'],
                    'address' => $employerData['company_address'],
                    'contact_phone' => $employerData['company_phone'],
                    'company_website' => $employerData['company_website'],
                    'is_verified' => $employerData['is_verified'],
                    'city' => 'Bengkalis',
                    'state' => 'Riau',
                    'country' => 'Indonesia',
                ]
            );
        }
    }
}
