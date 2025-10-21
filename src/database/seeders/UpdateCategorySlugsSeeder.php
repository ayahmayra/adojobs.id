<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class UpdateCategorySlugsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
                $category->save();
                $this->command->info("Updated slug for {$category->name}: {$category->slug}");
            }
        }
        
        $this->command->info("Updated slugs for {$categories->count()} categories.");
    }
}
