<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LocalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            LocalCategorySeeder::class,
            LocalSeekerSeeder::class,
            LocalEmployerSeeder::class,
            LocalJobSeeder::class,
            LocalArticleSeeder::class,
        ]);
    }
}
