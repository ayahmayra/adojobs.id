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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_postings')->onDelete('cascade');
            $table->foreignId('seeker_id')->constrained()->onDelete('cascade');
            
            // Application Details
            $table->text('cover_letter')->nullable();
            $table->string('cv_path')->nullable();
            $table->json('additional_documents')->nullable();
            
            // Status Tracking
            $table->enum('status', ['pending', 'reviewed', 'shortlisted', 'interview', 'offered', 'hired', 'rejected', 'withdrawn'])
                ->default('pending');
            $table->text('employer_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('shortlisted_at')->nullable();
            $table->timestamp('interview_at')->nullable();
            $table->timestamp('hired_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('job_id');
            $table->index('seeker_id');
            $table->index('status');
            $table->index('created_at');
            
            // Prevent duplicate applications
            $table->unique(['job_id', 'seeker_id'], 'unique_job_seeker');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};

