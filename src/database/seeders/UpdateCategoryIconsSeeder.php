<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCategoryIconsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping dari teks ke emoji
        $iconMappings = [
            'computer' => '💻',
            'megaphone' => '📢', 
            'palette' => '🎨',
            'calculator' => '🧮',
            'users' => '👥',
            'cog' => '⚙️',
            'heart' => '❤️',
            'briefcase' => '💼',
            'chart' => '📊',
            'building' => '🏢',
            'mobile' => '📱',
            'wrench' => '🔧',
            'globe' => '🌐',
            'paint' => '🎨',
            'money' => '💰',
            'car' => '🚗',
            'home' => '🏠',
            'hospital' => '🏥',
            'book' => '📚',
            'music' => '🎵',
            'camera' => '📷',
            'star' => '⭐',
            'lightbulb' => '💡',
            'target' => '🎯',
            'shield' => '🛡️',
        ];

        echo "Updating category icons from text to emoji...\n";

        foreach ($iconMappings as $oldIcon => $newIcon) {
            $updated = DB::table('categories')
                ->where('icon', $oldIcon)
                ->update(['icon' => $newIcon]);
            
            if ($updated > 0) {
                echo "Updated {$updated} categories from '{$oldIcon}' to '{$newIcon}'\n";
            }
        }

        // Update categories yang tidak ada icon dengan emoji default
        $defaultIcon = '📁';
        $updated = DB::table('categories')
            ->whereNull('icon')
            ->orWhere('icon', '')
            ->update(['icon' => $defaultIcon]);
        
        if ($updated > 0) {
            echo "Updated {$updated} categories with default icon '{$defaultIcon}'\n";
        }

        echo "Category icon update completed!\n";
    }
}
