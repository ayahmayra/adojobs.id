<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeaturedJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Menandai 6 lowongan terpilih sebagai featured jobs.
     * Featured jobs akan ditampilkan di homepage dan mendapat prioritas.
     */
    public function run(): void
    {
        // Reset semua featured jobs terlebih dahulu
        Job::where('is_featured', 1)->update(['is_featured' => 0]);
        
        $this->command->info('Reset all featured jobs...');

        // Pilih 6 lowongan dari berbagai kategori untuk dijadikan featured
        // Kriteria: Published, belum expired, berbagai kategori
        $featuredJobIds = [
            1,  // Pekerja Kebun Sawit (Pertanian)
            2,  // Asisten Dapur (Jasa)
            3,  // Kasir Toko Bangunan (Perdagangan)
            4,  // Perawat Rumah Sakit (Kesehatan)
            9,  // Tukang Bangunan (Konstruksi)
            10, // Guru SD (Pendidikan)
        ];

        $count = 0;
        foreach ($featuredJobIds as $jobId) {
            $job = Job::find($jobId);
            
            if ($job && $job->status === 'published') {
                $job->update(['is_featured' => 1]);
                $count++;
                $this->command->info("✓ Featured: {$job->title} (ID: {$jobId})");
            } else {
                $this->command->warn("⚠ Job ID {$jobId} not found or not published");
            }
        }

        $this->command->info('');
        $this->command->info("✓ Total {$count} jobs marked as featured!");
        
        // Show summary
        $this->command->info('');
        $this->command->info('Featured Jobs Summary:');
        $this->command->table(
            ['ID', 'Title', 'Category', 'Location', 'Type'],
            Job::where('is_featured', 1)
                ->with('category')
                ->get()
                ->map(fn($job) => [
                    $job->id,
                    $job->title,
                    $job->category->name ?? 'N/A',
                    $job->city . ', ' . $job->state,
                    $job->job_type,
                ])
        );
    }
}
