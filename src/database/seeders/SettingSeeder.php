<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'AdoJobs.id', 'type' => 'string', 'group' => 'general', 'description' => 'Website name'],
            ['key' => 'site_description', 'value' => 'Platform Lowongan Kerja Lokal Indonesia', 'type' => 'string', 'group' => 'general', 'description' => 'Site tagline'],
            ['key' => 'site_email', 'value' => 'info@adojobs.id', 'type' => 'string', 'group' => 'general', 'description' => 'Contact email'],
            ['key' => 'site_phone', 'value' => '+62 812-3456-7890', 'type' => 'string', 'group' => 'general', 'description' => 'Contact phone'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'general', 'description' => 'Enable maintenance mode'],
            
            // Job Settings
            ['key' => 'jobs_per_page', 'value' => '20', 'type' => 'integer', 'group' => 'jobs', 'description' => 'Number of jobs per page'],
            ['key' => 'featured_jobs_count', 'value' => '6', 'type' => 'integer', 'group' => 'jobs', 'description' => 'Number of featured jobs on homepage'],
            ['key' => 'auto_close_expired_jobs', 'value' => '1', 'type' => 'boolean', 'group' => 'jobs', 'description' => 'Automatically close expired jobs'],
            ['key' => 'default_job_duration_days', 'value' => '30', 'type' => 'integer', 'group' => 'jobs', 'description' => 'Default job listing duration in days'],
            
            // Application Settings
            ['key' => 'max_cv_size_mb', 'value' => '5', 'type' => 'integer', 'group' => 'applications', 'description' => 'Maximum CV file size in MB'],
            ['key' => 'allowed_cv_formats', 'value' => '["pdf", "doc", "docx"]', 'type' => 'json', 'group' => 'applications', 'description' => 'Allowed CV file formats'],
            ['key' => 'notify_employer_new_application', 'value' => '1', 'type' => 'boolean', 'group' => 'applications', 'description' => 'Notify employer of new applications'],
            
            // Email Settings
            ['key' => 'email_from_name', 'value' => 'AdoJobs.id', 'type' => 'string', 'group' => 'email', 'description' => 'Email sender name'],
            ['key' => 'email_from_address', 'value' => 'noreply@adojobs.id', 'type' => 'string', 'group' => 'email', 'description' => 'Email sender address'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}

