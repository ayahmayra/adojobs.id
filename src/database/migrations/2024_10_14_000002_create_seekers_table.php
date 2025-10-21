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
        Schema::create('seekers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Indonesia');
            $table->string('postal_code')->nullable();
            
            // Professional Details
            $table->string('current_job_title')->nullable();
            $table->text('bio')->nullable();
            $table->json('skills')->nullable(); // Array of skills
            $table->json('education')->nullable(); // Array of education history
            $table->json('experience')->nullable(); // Array of work experience
            $table->string('cv_path')->nullable();
            $table->string('resume_path')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            
            // Preferences
            $table->decimal('expected_salary_min', 12, 2)->nullable();
            $table->decimal('expected_salary_max', 12, 2)->nullable();
            $table->string('preferred_location')->nullable();
            $table->enum('job_type_preference', ['full-time', 'part-time', 'contract', 'freelance', 'internship'])->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seekers');
    }
};

