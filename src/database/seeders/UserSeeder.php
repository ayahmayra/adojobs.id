<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Seeker;
use App\Models\Employer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@jobmaker.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Employer Users with Company Profiles
        $this->createEmployer(
            'Tech Innovations Ltd',
            'employer1@jobmaker.local',
            'Sarah Johnson',
            'A leading technology company specializing in innovative software solutions and digital transformation services.',
            'Jakarta',
            'Software & Technology',
            '50-200'
        );

        $this->createEmployer(
            'Green Energy Solutions',
            'employer2@jobmaker.local',
            'Michael Chen',
            'Pioneering sustainable energy solutions for a greener future. We develop and implement renewable energy projects.',
            'Surabaya',
            'Energy & Environment',
            '200-500'
        );

        $this->createEmployer(
            'Creative Digital Agency',
            'employer3@jobmaker.local',
            'Amanda Williams',
            'Award-winning digital agency specializing in branding, web design, and digital marketing campaigns.',
            'Bandung',
            'Marketing & Advertising',
            '10-50'
        );

        $this->createEmployer(
            'Global Finance Corp',
            'employer4@jobmaker.local',
            'Robert Martinez',
            'International financial services company providing investment banking, wealth management, and advisory services.',
            'Jakarta',
            'Finance & Banking',
            '500+'
        );

        $this->createEmployer(
            'HealthCare Plus',
            'employer5@jobmaker.local',
            'Dr. Emily Brown',
            'Modern healthcare facility providing comprehensive medical services with state-of-the-art equipment.',
            'Yogyakarta',
            'Healthcare',
            '50-200'
        );

        // Create Job Seeker Users with Profiles
        $this->createSeeker(
            'John Doe',
            'seeker1@jobmaker.local',
            'Full Stack Developer',
            'Jakarta',
            ['PHP', 'Laravel', 'JavaScript', 'React', 'MySQL', 'Docker'],
            5000000,
            8000000
        );

        $this->createSeeker(
            'Jane Smith',
            'seeker2@jobmaker.local',
            'UI/UX Designer',
            'Bandung',
            ['Figma', 'Adobe XD', 'Sketch', 'UI Design', 'User Research', 'Prototyping'],
            4000000,
            7000000
        );

        $this->createSeeker(
            'David Kumar',
            'seeker3@jobmaker.local',
            'Digital Marketing Specialist',
            'Surabaya',
            ['SEO', 'Google Ads', 'Social Media Marketing', 'Content Marketing', 'Analytics'],
            3500000,
            6000000
        );

        $this->createSeeker(
            'Maria Garcia',
            'seeker4@jobmaker.local',
            'Financial Analyst',
            'Jakarta',
            ['Financial Modeling', 'Excel', 'Data Analysis', 'Forecasting', 'Reporting'],
            6000000,
            9000000
        );

        $this->createSeeker(
            'Ahmad Rahman',
            'seeker5@jobmaker.local',
            'Software Engineer',
            'Jakarta',
            ['Python', 'Django', 'PostgreSQL', 'AWS', 'CI/CD', 'Microservices'],
            7000000,
            12000000
        );

        $this->createSeeker(
            'Siti Nurhaliza',
            'seeker6@jobmaker.local',
            'Human Resources Manager',
            'Bandung',
            ['Recruitment', 'Employee Relations', 'Performance Management', 'Training'],
            5000000,
            8000000
        );
    }

    private function createEmployer(
        string $companyName,
        string $email,
        string $contactPerson,
        string $description,
        string $city,
        string $industry,
        string $companySize
    ): void {
        $user = User::create([
            'name' => $contactPerson,
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => 'employer',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Employer::create([
            'user_id' => $user->id,
            'company_name' => $companyName,
            'company_description' => $description,
            'company_size' => $companySize,
            'industry' => $industry,
            'founded_year' => rand(2005, 2020),
            'contact_person' => $contactPerson,
            'contact_phone' => '+62 ' . rand(811, 899) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999),
            'contact_email' => $email,
            'address' => 'Jl. ' . ucfirst($industry) . ' No. ' . rand(10, 99),
            'city' => $city,
            'state' => 'Jawa',
            'country' => 'Indonesia',
            'postal_code' => (string) rand(10000, 99999),
            'is_verified' => true,
            'verified_at' => now(),
        ]);
    }

    private function createSeeker(
        string $name,
        string $email,
        string $jobTitle,
        string $city,
        array $skills,
        int $salaryMin,
        int $salaryMax
    ): void {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => 'seeker',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Seeker::create([
            'user_id' => $user->id,
            'phone' => '+62 ' . rand(811, 899) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999),
            'date_of_birth' => now()->subYears(rand(25, 40))->format('Y-m-d'),
            'gender' => ['male', 'female'][rand(0, 1)],
            'address' => 'Jl. ' . ucfirst($city) . ' No. ' . rand(10, 99),
            'city' => $city,
            'state' => 'Jawa',
            'country' => 'Indonesia',
            'postal_code' => (string) rand(10000, 99999),
            'current_job_title' => $jobTitle,
            'bio' => "Experienced {$jobTitle} with a proven track record of delivering high-quality work. Passionate about learning new technologies and solving complex problems.",
            'skills' => $skills,
            'education' => [
                [
                    'degree' => "Bachelor's Degree",
                    'field' => 'Computer Science',
                    'institution' => 'University of Indonesia',
                    'year' => (string) rand(2010, 2018),
                ]
            ],
            'experience' => [
                [
                    'title' => $jobTitle,
                    'company' => 'Previous Company Ltd',
                    'duration' => rand(2, 5) . ' years',
                    'description' => 'Responsible for various projects and initiatives',
                ]
            ],
            'expected_salary_min' => $salaryMin,
            'expected_salary_max' => $salaryMax,
            'preferred_location' => $city,
            'job_type_preference' => 'full-time',
        ]);
    }
}

