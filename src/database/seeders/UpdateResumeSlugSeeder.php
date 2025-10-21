<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateResumeSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all users that don't have resume_slug
        $users = User::whereNull('resume_slug')->get();
        
        foreach ($users as $user) {
            $user->resume_slug = User::generateResumeSlug($user->email);
            $user->save();
            
            $this->command->info("Generated resume slug for: {$user->email} → {$user->resume_slug}");
        }
        
        $this->command->info("Updated {$users->count()} users with resume slugs.");
    }
}
