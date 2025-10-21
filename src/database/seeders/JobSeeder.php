<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Employer;
use App\Models\Category;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itCategory = Category::where('slug', 'information-technology')->first();
        $marketingCategory = Category::where('slug', 'marketing-sales')->first();
        $designCategory = Category::where('slug', 'design-creative')->first();
        $financeCategory = Category::where('slug', 'finance-accounting')->first();
        $healthcareCategory = Category::where('slug', 'healthcare-medical')->first();

        $employers = Employer::all();

        // Tech Innovations Ltd Jobs
        $techEmployer = $employers->first();
        
        Job::create([
            'employer_id' => $techEmployer->id,
            'category_id' => $itCategory->id,
            'title' => 'Senior Full Stack Developer',
            'description' => 'We are seeking an experienced Full Stack Developer to join our growing team. You will be responsible for developing and maintaining web applications using modern technologies.',
            'requirements' => "- 5+ years of experience in web development\n- Strong knowledge of Laravel and React\n- Experience with MySQL and NoSQL databases\n- Familiarity with Docker and CI/CD\n- Excellent problem-solving skills",
            'responsibilities' => "- Develop and maintain web applications\n- Write clean, maintainable code\n- Collaborate with cross-functional teams\n- Participate in code reviews\n- Mentor junior developers",
            'benefits' => "- Competitive salary\n- Health insurance\n- Remote work options\n- Professional development budget\n- Modern office environment",
            'job_type' => 'full-time',
            'work_mode' => 'hybrid',
            'location' => $techEmployer->city,
            'city' => $techEmployer->city,
            'country' => 'Indonesia',
            'salary_min' => 12000000,
            'salary_max' => 18000000,
            'salary_period' => 'monthly',
            'salary_currency' => 'IDR',
            'experience_level' => 'Senior',
            'experience_years_min' => 5,
            'experience_years_max' => 10,
            'education_level' => "Bachelor's Degree",
            'required_skills' => ['PHP', 'Laravel', 'JavaScript', 'React', 'MySQL', 'Docker'],
            'preferred_skills' => ['AWS', 'Redis', 'GraphQL'],
            'vacancies' => 2,
            'application_deadline' => now()->addDays(30),
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        Job::create([
            'employer_id' => $techEmployer->id,
            'category_id' => $itCategory->id,
            'title' => 'DevOps Engineer',
            'description' => 'Join our infrastructure team to build and maintain scalable cloud infrastructure. You will work on automation, monitoring, and deployment systems.',
            'requirements' => "- 3+ years of DevOps experience\n- Strong knowledge of AWS or Azure\n- Experience with Kubernetes and Docker\n- Proficiency in scripting (Python, Bash)\n- Understanding of CI/CD pipelines",
            'responsibilities' => "- Manage cloud infrastructure\n- Implement automation solutions\n- Monitor system performance\n- Ensure security best practices\n- Support development teams",
            'benefits' => "- Competitive salary\n- Health insurance\n- Learning budget\n- Flexible hours\n- Remote work options",
            'job_type' => 'full-time',
            'work_mode' => 'remote',
            'location' => 'Remote',
            'city' => $techEmployer->city,
            'country' => 'Indonesia',
            'salary_min' => 15000000,
            'salary_max' => 22000000,
            'salary_period' => 'monthly',
            'salary_currency' => 'IDR',
            'experience_level' => 'Mid Level',
            'experience_years_min' => 3,
            'experience_years_max' => 7,
            'education_level' => "Bachelor's Degree",
            'required_skills' => ['AWS', 'Docker', 'Kubernetes', 'Python', 'CI/CD'],
            'preferred_skills' => ['Terraform', 'Ansible', 'Prometheus'],
            'vacancies' => 1,
            'application_deadline' => now()->addDays(25),
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        // Green Energy Solutions Jobs
        $energyEmployer = $employers->skip(1)->first();

        Job::create([
            'employer_id' => $energyEmployer->id,
            'category_id' => Category::where('slug', 'engineering')->first()?->id,
            'title' => 'Renewable Energy Engineer',
            'description' => 'We are looking for a passionate engineer to work on cutting-edge renewable energy projects. You will be involved in the design and implementation of solar and wind energy systems.',
            'requirements' => "- Bachelor's in Electrical/Mechanical Engineering\n- 2+ years in renewable energy\n- Knowledge of solar/wind systems\n- CAD software proficiency\n- Project management skills",
            'responsibilities' => "- Design renewable energy systems\n- Conduct site assessments\n- Manage project implementation\n- Collaborate with clients\n- Prepare technical documentation",
            'benefits' => "- Competitive salary\n- Health insurance\n- Professional certifications\n- Career growth opportunities",
            'job_type' => 'full-time',
            'work_mode' => 'on-site',
            'location' => $energyEmployer->city,
            'city' => $energyEmployer->city,
            'country' => 'Indonesia',
            'salary_min' => 10000000,
            'salary_max' => 15000000,
            'salary_period' => 'monthly',
            'salary_currency' => 'IDR',
            'experience_level' => 'Mid Level',
            'experience_years_min' => 2,
            'experience_years_max' => 5,
            'education_level' => "Bachelor's Degree",
            'required_skills' => ['Renewable Energy', 'CAD', 'Project Management'],
            'vacancies' => 3,
            'application_deadline' => now()->addDays(20),
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Creative Digital Agency Jobs
        $creativeEmployer = $employers->skip(2)->first();

        Job::create([
            'employer_id' => $creativeEmployer->id,
            'category_id' => $designCategory->id,
            'title' => 'UI/UX Designer',
            'description' => 'Join our creative team to design beautiful and intuitive user interfaces for web and mobile applications. You will work closely with clients and developers.',
            'requirements' => "- 3+ years of UI/UX design experience\n- Proficiency in Figma and Adobe XD\n- Strong portfolio of design projects\n- Understanding of user-centered design\n- Excellent communication skills",
            'responsibilities' => "- Create user interface designs\n- Conduct user research\n- Develop wireframes and prototypes\n- Collaborate with development team\n- Present designs to clients",
            'benefits' => "- Creative work environment\n- Health insurance\n- Flexible hours\n- Latest design tools\n- Team outings",
            'job_type' => 'full-time',
            'work_mode' => 'hybrid',
            'location' => $creativeEmployer->city,
            'city' => $creativeEmployer->city,
            'country' => 'Indonesia',
            'salary_min' => 8000000,
            'salary_max' => 12000000,
            'salary_period' => 'monthly',
            'salary_currency' => 'IDR',
            'experience_level' => 'Mid Level',
            'experience_years_min' => 3,
            'experience_years_max' => 6,
            'education_level' => "Bachelor's Degree",
            'required_skills' => ['Figma', 'Adobe XD', 'UI Design', 'UX Research', 'Prototyping'],
            'preferred_skills' => ['Sketch', 'After Effects', 'Illustration'],
            'vacancies' => 2,
            'application_deadline' => now()->addDays(28),
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        Job::create([
            'employer_id' => $creativeEmployer->id,
            'category_id' => $marketingCategory->id,
            'title' => 'Digital Marketing Manager',
            'description' => 'Lead our digital marketing initiatives and drive growth for our clients. You will manage campaigns across multiple channels and analyze performance metrics.',
            'requirements' => "- 4+ years in digital marketing\n- Experience with Google Ads and Facebook Ads\n- Strong analytical skills\n- SEO/SEM expertise\n- Team management experience",
            'responsibilities' => "- Develop marketing strategies\n- Manage digital campaigns\n- Analyze campaign performance\n- Lead marketing team\n- Client relationship management",
            'benefits' => "- Competitive salary\n- Performance bonuses\n- Health insurance\n- Professional development\n- Modern office",
            'job_type' => 'full-time',
            'work_mode' => 'hybrid',
            'location' => $creativeEmployer->city,
            'city' => $creativeEmployer->city,
            'country' => 'Indonesia',
            'salary_min' => 10000000,
            'salary_max' => 16000000,
            'salary_period' => 'monthly',
            'salary_currency' => 'IDR',
            'experience_level' => 'Senior',
            'experience_years_min' => 4,
            'experience_years_max' => 8,
            'education_level' => "Bachelor's Degree",
            'required_skills' => ['Digital Marketing', 'Google Ads', 'SEO', 'Analytics', 'Team Management'],
            'vacancies' => 1,
            'application_deadline' => now()->addDays(22),
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Global Finance Corp Jobs
        $financeEmployer = $employers->skip(3)->first();

        Job::create([
            'employer_id' => $financeEmployer->id,
            'category_id' => $financeCategory->id,
            'title' => 'Financial Analyst',
            'description' => 'We are seeking a detail-oriented Financial Analyst to support our investment banking team. You will analyze financial data and provide insights for decision-making.',
            'requirements' => "- Bachelor's in Finance/Accounting\n- 2+ years of financial analysis experience\n- Advanced Excel skills\n- Knowledge of financial modeling\n- CFA certification (preferred)",
            'responsibilities' => "- Analyze financial statements\n- Create financial models\n- Prepare investment reports\n- Support M&A activities\n- Present findings to management",
            'benefits' => "- Excellent compensation\n- Performance bonuses\n- Health insurance\n- Professional certifications\n- Career advancement",
            'job_type' => 'full-time',
            'work_mode' => 'on-site',
            'location' => $financeEmployer->city,
            'city' => $financeEmployer->city,
            'country' => 'Indonesia',
            'salary_min' => 12000000,
            'salary_max' => 18000000,
            'salary_period' => 'monthly',
            'salary_currency' => 'IDR',
            'experience_level' => 'Mid Level',
            'experience_years_min' => 2,
            'experience_years_max' => 5,
            'education_level' => "Bachelor's Degree",
            'required_skills' => ['Financial Analysis', 'Excel', 'Financial Modeling', 'Accounting'],
            'preferred_skills' => ['CFA', 'Bloomberg Terminal', 'SQL'],
            'vacancies' => 2,
            'application_deadline' => now()->addDays(35),
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        // HealthCare Plus Jobs
        $healthcareEmployer = $employers->skip(4)->first();

        Job::create([
            'employer_id' => $healthcareEmployer->id,
            'category_id' => $healthcareCategory->id,
            'title' => 'Registered Nurse',
            'description' => 'Join our healthcare team to provide quality patient care. We are looking for compassionate and skilled nurses to work in our modern facility.',
            'requirements' => "- Nursing degree and valid license\n- 1+ years of clinical experience\n- BLS/ACLS certification\n- Excellent patient care skills\n- Ability to work shifts",
            'responsibilities' => "- Provide patient care\n- Administer medications\n- Monitor vital signs\n- Maintain patient records\n- Collaborate with medical team",
            'benefits' => "- Competitive salary\n- Health insurance\n- Retirement plan\n- Continuing education\n- Supportive environment",
            'job_type' => 'full-time',
            'work_mode' => 'on-site',
            'location' => $healthcareEmployer->city,
            'city' => $healthcareEmployer->city,
            'country' => 'Indonesia',
            'salary_min' => 6000000,
            'salary_max' => 9000000,
            'salary_period' => 'monthly',
            'salary_currency' => 'IDR',
            'experience_level' => 'Entry Level',
            'experience_years_min' => 1,
            'experience_years_max' => 3,
            'education_level' => "Bachelor's Degree",
            'required_skills' => ['Patient Care', 'Clinical Skills', 'BLS', 'Medical Records'],
            'vacancies' => 5,
            'application_deadline' => now()->addDays(15),
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Add some more jobs for variety
        Job::create([
            'employer_id' => $techEmployer->id,
            'category_id' => $itCategory->id,
            'title' => 'Junior Frontend Developer',
            'description' => 'Start your career with us as a Junior Frontend Developer. Great opportunity to learn and grow with experienced mentors.',
            'requirements' => "- Fresh graduate or 1 year experience\n- Knowledge of HTML, CSS, JavaScript\n- Familiarity with React or Vue.js\n- Passionate about web development\n- Good communication skills",
            'responsibilities' => "- Build user interfaces\n- Learn from senior developers\n- Write clean code\n- Participate in team meetings\n- Contribute to projects",
            'benefits' => "- Training and mentorship\n- Health insurance\n- Snacks and drinks\n- Modern workspace\n- Career growth",
            'job_type' => 'full-time',
            'work_mode' => 'on-site',
            'location' => $techEmployer->city,
            'city' => $techEmployer->city,
            'country' => 'Indonesia',
            'salary_min' => 5000000,
            'salary_max' => 7000000,
            'salary_period' => 'monthly',
            'salary_currency' => 'IDR',
            'experience_level' => 'Entry Level',
            'experience_years_min' => 0,
            'experience_years_max' => 2,
            'education_level' => "Bachelor's Degree",
            'required_skills' => ['HTML', 'CSS', 'JavaScript', 'React'],
            'vacancies' => 3,
            'application_deadline' => now()->addDays(40),
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}

