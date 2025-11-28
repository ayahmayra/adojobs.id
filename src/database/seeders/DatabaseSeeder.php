<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,            // ⚡ MUST RUN FIRST - Creates admin user
            SettingSeeder::class,
            LocalCategorySeeder::class,
            LocalSeekerSeeder::class,
            LocalEmployerSeeder::class,
            LocalJobSeeder::class,
            FeaturedJobSeeder::class,      // Run after LocalJobSeeder
            ApplicationSeeder::class,
            ConversationSeeder::class,
            LocalArticleSeeder::class,     // Needs admin for article authors
        ]);
    }
}
