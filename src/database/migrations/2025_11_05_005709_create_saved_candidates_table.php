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
        Schema::create('saved_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('employers')->onDelete('cascade');
            $table->foreignId('seeker_id')->constrained('seekers')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('employer_id');
            $table->index('seeker_id');
            
            // Prevent duplicate saves (one employer can save one seeker only once)
            $table->unique(['employer_id', 'seeker_id'], 'unique_saved_candidate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_candidates');
    }
};
