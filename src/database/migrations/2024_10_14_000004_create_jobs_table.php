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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            // Job Details
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('benefits')->nullable();
            
            // Job Type & Location
            $table->enum('job_type', ['full-time', 'part-time', 'contract', 'freelance', 'internship'])->default('full-time');
            $table->enum('work_mode', ['on-site', 'remote', 'hybrid'])->default('on-site');
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Indonesia');
            
            // Salary
            $table->decimal('salary_min', 12, 2)->nullable();
            $table->decimal('salary_max', 12, 2)->nullable();
            $table->enum('salary_period', ['hourly', 'daily', 'monthly', 'yearly'])->default('monthly');
            $table->boolean('salary_negotiable')->default(false);
            $table->string('salary_currency')->default('IDR');
            
            // Experience & Education
            $table->string('experience_level')->nullable(); // e.g., "Entry Level", "Mid Level", "Senior"
            $table->integer('experience_years_min')->nullable();
            $table->integer('experience_years_max')->nullable();
            $table->string('education_level')->nullable(); // e.g., "High School", "Bachelor's", "Master's"
            
            // Skills
            $table->json('required_skills')->nullable();
            $table->json('preferred_skills')->nullable();
            
            // Application Details
            $table->integer('vacancies')->default(1);
            $table->date('application_deadline')->nullable();
            $table->string('application_email')->nullable();
            $table->string('application_url')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'published', 'closed', 'filled'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            
            // Metadata
            $table->integer('views_count')->default(0);
            $table->integer('applications_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('employer_id');
            $table->index('category_id');
            $table->index('slug');
            $table->index('status');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index('application_deadline');
            $table->index(['city', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};

