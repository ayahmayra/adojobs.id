<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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

        foreach ($iconMappings as $oldIcon => $newIcon) {
            DB::table('categories')
                ->where('icon', $oldIcon)
                ->update(['icon' => $newIcon]);
        }

        // Update categories yang tidak ada icon dengan emoji default
        DB::table('categories')
            ->whereNull('icon')
            ->orWhere('icon', '')
            ->update(['icon' => '📁']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mapping dari emoji ke teks (untuk rollback)
        $iconMappings = [
            '💻' => 'computer',
            '📢' => 'megaphone', 
            '🎨' => 'palette',
            '🧮' => 'calculator',
            '👥' => 'users',
            '⚙️' => 'cog',
            '❤️' => 'heart',
            '💼' => 'briefcase',
            '📊' => 'chart',
            '🏢' => 'building',
            '📱' => 'mobile',
            '🔧' => 'wrench',
            '🌐' => 'globe',
            '💰' => 'money',
            '🚗' => 'car',
            '🏠' => 'home',
            '🏥' => 'hospital',
            '📚' => 'book',
            '🎵' => 'music',
            '📷' => 'camera',
            '⭐' => 'star',
            '💡' => 'lightbulb',
            '🎯' => 'target',
            '🛡️' => 'shield',
        ];

        foreach ($iconMappings as $emoji => $text) {
            DB::table('categories')
                ->where('icon', $emoji)
                ->update(['icon' => $text]);
        }

        // Update default emoji back to empty
        DB::table('categories')
            ->where('icon', '📁')
            ->update(['icon' => null]);
    }
};