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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seeker_id')->constrained('seekers')->onDelete('cascade');
            $table->foreignId('employer_id')->constrained('employers')->onDelete('cascade');
            $table->foreignId('job_id')->nullable()->constrained('job_postings')->onDelete('set null');
            $table->string('subject');
            $table->timestamp('last_message_at')->nullable();
            $table->integer('seeker_unread_count')->default(0);
            $table->integer('employer_unread_count')->default(0);
            $table->boolean('is_archived')->default(false);
            $table->timestamps();

            // Indexes for performance
            $table->index(['seeker_id', 'employer_id', 'job_id']);
            $table->index('last_message_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
