<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Job;
use App\Models\Seeker;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = Job::all();
        $seekers = Seeker::all();

        // Create sample applications
        if ($jobs->count() > 0 && $seekers->count() > 0) {
            // Seeker 1 applies to multiple jobs
            $seeker1 = $seekers->first();
            $job1 = $jobs->first();
            
            if ($seeker1 && $job1) {
                Application::create([
                    'job_id' => $job1->id,
                    'seeker_id' => $seeker1->id,
                    'cover_letter' => 'I am very interested in this position and believe my skills align well with your requirements. I have extensive experience in full-stack development and would love to contribute to your team.',
                    'status' => 'pending',
                    'created_at' => now()->subDays(5),
                ]);
            }

            // Create more applications with different statuses
            if ($jobs->count() > 2 && $seekers->count() > 1) {
                // Application - reviewed
                Application::create([
                    'job_id' => $jobs->skip(1)->first()->id,
                    'seeker_id' => $seekers->skip(1)->first()->id,
                    'cover_letter' => 'I am passionate about DevOps and cloud technologies. My experience with AWS and Kubernetes makes me a great fit for this role.',
                    'status' => 'reviewed',
                    'reviewed_at' => now()->subDays(2),
                    'created_at' => now()->subDays(7),
                ]);

                // Application - shortlisted
                if ($seekers->count() > 2) {
                    Application::create([
                        'job_id' => $jobs->skip(3)->first()->id,
                        'seeker_id' => $seekers->skip(2)->first()->id,
                        'cover_letter' => 'As a UI/UX designer with a strong portfolio, I am excited about the opportunity to work with your creative team.',
                        'status' => 'shortlisted',
                        'reviewed_at' => now()->subDays(3),
                        'shortlisted_at' => now()->subDays(1),
                        'created_at' => now()->subDays(10),
                    ]);
                }

                // Application - rejected
                if ($seekers->count() > 3) {
                    Application::create([
                        'job_id' => $jobs->skip(2)->first()->id,
                        'seeker_id' => $seekers->skip(3)->first()->id,
                        'cover_letter' => 'I would like to apply for this engineering position.',
                        'status' => 'rejected',
                        'reviewed_at' => now()->subDays(4),
                        'rejected_at' => now()->subDays(3),
                        'rejection_reason' => 'Thank you for your application. While your qualifications are impressive, we have decided to move forward with candidates whose experience more closely matches our current needs.',
                        'created_at' => now()->subDays(12),
                    ]);
                }

                // More pending applications
                if ($jobs->count() > 4 && $seekers->count() > 4) {
                    Application::create([
                        'job_id' => $jobs->skip(4)->first()->id,
                        'seeker_id' => $seekers->skip(4)->first()->id,
                        'cover_letter' => 'I am very interested in joining your marketing team and helping drive digital growth for your clients.',
                        'status' => 'pending',
                        'created_at' => now()->subDays(3),
                    ]);

                    Application::create([
                        'job_id' => $jobs->skip(5)->first()->id,
                        'seeker_id' => $seekers->skip(5)->first()->id,
                        'cover_letter' => 'With my background in finance and strong analytical skills, I believe I would be a valuable addition to your team.',
                        'status' => 'pending',
                        'created_at' => now()->subDays(1),
                    ]);
                }

                // Cross applications - same seeker applying to multiple jobs
                if ($seekers->count() > 0 && $jobs->count() > 6) {
                    Application::create([
                        'job_id' => $jobs->skip(6)->first()->id,
                        'seeker_id' => $seeker1->id,
                        'cover_letter' => 'As an experienced professional, I am looking for new opportunities to grow my career.',
                        'status' => 'pending',
                        'created_at' => now()->subDays(2),
                    ]);
                }
            }
        }
    }
}

