<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employer;
use Illuminate\Support\Str;

class UpdateEmployerSlugsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employers = Employer::whereNull('slug')->get();
        
        foreach ($employers as $employer) {
            $slug = $employer->generateUniqueSlug();
            $employer->update(['slug' => $slug]);
            
            $this->command->info("Updated slug for {$employer->company_name}: {$slug}");
        }
        
        $this->command->info("Updated slugs for {$employers->count()} employers.");
    }
}