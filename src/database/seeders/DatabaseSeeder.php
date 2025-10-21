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
            SettingSeeder::class,
            LocalCategorySeeder::class,
            LocalSeekerSeeder::class,
            LocalEmployerSeeder::class,
            LocalJobSeeder::class,
            ApplicationSeeder::class,
            ConversationSeeder::class,
            LocalArticleSeeder::class,
        ]);
    }
}
