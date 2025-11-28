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
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Company Information
            $table->string('company_name');
            $table->string('company_slug')->unique();
            $table->text('company_description')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_size')->nullable(); // e.g., "1-10", "11-50", "51-200", etc.
            $table->string('industry')->nullable();
            $table->year('founded_year')->nullable();
            
            // Contact Information
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            
            // Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Indonesia');
            $table->string('postal_code')->nullable();
            
            // Social Media
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('facebook_url')->nullable();
            
            // Verification
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('company_slug');
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};

