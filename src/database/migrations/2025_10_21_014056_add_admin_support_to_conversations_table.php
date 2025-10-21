<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            // Drop existing foreign keys first
            $table->dropForeign(['seeker_id']);
            $table->dropForeign(['employer_id']);
        });
        
        Schema::table('conversations', function (Blueprint $table) {
            // Make seeker_id and employer_id nullable
            $table->unsignedBigInteger('seeker_id')->nullable()->change();
            $table->unsignedBigInteger('employer_id')->nullable()->change();
            
            // Re-add foreign keys as nullable
            $table->foreign('seeker_id')->references('id')->on('seekers')->onDelete('cascade');
            $table->foreign('employer_id')->references('id')->on('employers')->onDelete('cascade');
            
            // Add admin_id for conversations involving admin
            $table->foreignId('admin_id')->nullable()->after('employer_id')
                ->constrained('users')->onDelete('cascade');
            
            // Add admin unread count
            $table->integer('admin_unread_count')->default(0)->after('employer_unread_count');
            
            // Add index for admin conversations
            $table->index('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn(['admin_id', 'admin_unread_count']);
            $table->dropIndex(['admin_id']);
            
            // Revert nullable changes (this might cause issues if there are admin conversations)
            // $table->foreignId('seeker_id')->nullable(false)->change();
            // $table->foreignId('employer_id')->nullable(false)->change();
        });
    }
};
