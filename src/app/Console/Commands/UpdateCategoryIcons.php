<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCategoryIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:update-icons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update category icons from text to emoji';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating category icons from text to emoji...');

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

        $totalUpdated = 0;

        foreach ($iconMappings as $oldIcon => $newIcon) {
            $updated = DB::table('categories')
                ->where('icon', $oldIcon)
                ->update(['icon' => $newIcon]);
            
            if ($updated > 0) {
                $this->line("Updated {$updated} categories from '{$oldIcon}' to '{$newIcon}'");
                $totalUpdated += $updated;
            }
        }

        // Update categories yang tidak ada icon dengan emoji default
        $defaultIcon = '📁';
        $updated = DB::table('categories')
            ->whereNull('icon')
            ->orWhere('icon', '')
            ->update(['icon' => $defaultIcon]);
        
        if ($updated > 0) {
            $this->line("Updated {$updated} categories with default icon '{$defaultIcon}'");
            $totalUpdated += $updated;
        }

        $this->info("Total categories updated: {$totalUpdated}");
        $this->info('Category icon update completed!');
    }
}