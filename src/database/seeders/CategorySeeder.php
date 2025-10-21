<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Information Technology',
                'slug' => 'information-technology',
                'description' => 'Software development, IT support, network administration, and more',
                'icon' => 'computer',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'Marketing & Sales',
                'slug' => 'marketing-sales',
                'description' => 'Digital marketing, sales, business development, and advertising',
                'icon' => 'megaphone',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Design & Creative',
                'slug' => 'design-creative',
                'description' => 'Graphic design, UI/UX design, creative direction, and multimedia',
                'icon' => 'palette',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Finance & Accounting',
                'slug' => 'finance-accounting',
                'description' => 'Accounting, financial analysis, auditing, and bookkeeping',
                'icon' => 'calculator',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => 'Human Resources',
                'slug' => 'human-resources',
                'description' => 'HR management, recruitment, training, and employee relations',
                'icon' => 'users',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => 'Engineering',
                'slug' => 'engineering',
                'description' => 'Mechanical, electrical, civil, and industrial engineering',
                'icon' => 'cog',
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name' => 'Healthcare & Medical',
                'slug' => 'healthcare-medical',
                'description' => 'Medical professionals, nursing, healthcare administration',
                'icon' => 'heart',
                'is_active' => true,
                'order' => 7,
            ],
            [
                'name' => 'Education & Training',
                'slug' => 'education-training',
                'description' => 'Teaching, training, curriculum development, and education management',
                'icon' => 'book',
                'is_active' => true,
                'order' => 8,
            ],
            [
                'name' => 'Customer Service',
                'slug' => 'customer-service',
                'description' => 'Customer support, client relations, and call center operations',
                'icon' => 'headset',
                'is_active' => true,
                'order' => 9,
            ],
            [
                'name' => 'Administrative',
                'slug' => 'administrative',
                'description' => 'Office administration, secretarial, and clerical work',
                'icon' => 'briefcase',
                'is_active' => true,
                'order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

