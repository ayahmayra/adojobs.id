<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class LocalCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pertanian & Perkebunan',
                'slug' => 'pertanian-perkebunan',
                'description' => 'Pekerjaan di bidang pertanian, perkebunan, dan budidaya tanaman',
                'icon' => '🌾',
                'is_active' => true,
            ],
            [
                'name' => 'Kebersihan & Pemeliharaan',
                'slug' => 'kebersihan-pemeliharaan',
                'description' => 'Pekerjaan kebersihan, pemeliharaan, dan perawatan lingkungan',
                'icon' => '🧹',
                'is_active' => true,
            ],
            [
                'name' => 'Asisten Rumah Tangga',
                'slug' => 'asisten-rumah-tangga',
                'description' => 'Pekerjaan membantu pekerjaan rumah tangga dan perawatan keluarga',
                'icon' => '🏠',
                'is_active' => true,
            ],
            [
                'name' => 'Administrasi & Kantor',
                'slug' => 'administrasi-kantor',
                'description' => 'Pekerjaan administrasi, tata usaha, dan pekerjaan kantor',
                'icon' => '📋',
                'is_active' => true,
            ],
            [
                'name' => 'Perdagangan & Jasa',
                'slug' => 'perdagangan-jasa',
                'description' => 'Pekerjaan di bidang perdagangan, jasa, dan bisnis lokal',
                'icon' => '🛒',
                'is_active' => true,
            ],
            [
                'name' => 'Konstruksi & Bangunan',
                'slug' => 'konstruksi-bangunan',
                'description' => 'Pekerjaan konstruksi, bangunan, dan renovasi',
                'icon' => '🔨',
                'is_active' => true,
            ],
            [
                'name' => 'Transportasi & Logistik',
                'slug' => 'transportasi-logistik',
                'description' => 'Pekerjaan transportasi, pengiriman, dan logistik lokal',
                'icon' => '🚚',
                'is_active' => true,
            ],
            [
                'name' => 'Kuliner & Makanan',
                'slug' => 'kuliner-makanan',
                'description' => 'Pekerjaan di bidang kuliner, makanan, dan minuman',
                'icon' => '🍽️',
                'is_active' => true,
            ],
            [
                'name' => 'Kesehatan & Perawatan',
                'slug' => 'kesehatan-perawatan',
                'description' => 'Pekerjaan di bidang kesehatan, perawatan, dan medis',
                'icon' => '🏥',
                'is_active' => true,
            ],
            [
                'name' => 'Pendidikan & Pelatihan',
                'slug' => 'pendidikan-pelatihan',
                'description' => 'Pekerjaan di bidang pendidikan, pelatihan, dan pengajaran',
                'icon' => '📚',
                'is_active' => true,
            ],
            [
                'name' => 'Keamanan & Satpam',
                'slug' => 'keamanan-satpam',
                'description' => 'Pekerjaan keamanan, satpam, dan penjaga',
                'icon' => '🛡️',
                'is_active' => true,
            ],
            [
                'name' => 'Teknologi & Digital',
                'slug' => 'teknologi-digital',
                'description' => 'Pekerjaan di bidang teknologi, digital, dan komputer',
                'icon' => '💻',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
