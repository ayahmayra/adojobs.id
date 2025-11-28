<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'AdoJobs.id',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nama website yang ditampilkan di seluruh aplikasi',
            ],
            [
                'key' => 'site_description',
                'value' => 'Platform pencarian kerja terbaik di Pulau Bengkalis. Temukan pekerjaan impian Anda atau rekrut kandidat terbaik dengan mudah.',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Deskripsi singkat tentang website',
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'string',
                'group' => 'general',
                'description' => 'Path ke file logo website',
            ],
            [
                'key' => 'site_favicon',
                'value' => null,
                'type' => 'string',
                'group' => 'general',
                'description' => 'Path ke file favicon',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✓ General settings seeded successfully');
    }
}
