<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JobModuleSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(JobCategorySeeder::class);

        $now = now()->toDateTimeString();

        $itId  = DB::table('job_categories')->where('slug', 'information-technology')->value('id');
        $mktId = DB::table('job_categories')->where('slug', 'marketing-and-sales')->value('id');
        $hrId  = DB::table('job_categories')->where('slug', 'human-resources')->value('id');

        // ── Admin User ────────────────────────────────────────────────────────
        if (! DB::table('users')->where('email', 'admin@gmail.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Super Admin', 'email' => 'admin@gmail.com',
                'password' => Hash::make('password'), 'role' => 'admin',
                'email_verified_at' => $now, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // ── Job Keeper User ───────────────────────────────────────────────────
        if (! DB::table('users')->where('email', 'keeper@gmail.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'TechCorp Recruiter', 'email' => 'keeper@gmail.com',
                'password' => Hash::make('password'), 'role' => 'job_keeper',
                'email_verified_at' => $now, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        $keeperId = DB::table('users')->where('email', 'keeper@gmail.com')->value('id');

        // ── Company ───────────────────────────────────────────────────────────
        if (! DB::table('companies')->where('email', 'hr@techcorp.com')->exists()) {
            DB::table('companies')->insert([
                'user_id' => $keeperId, 'name' => 'TechCorp India Pvt. Ltd.',
                'slug' => 'techcorp-india-pvt-ltd', 'email' => 'hr@techcorp.com',
                'phone' => '+91-9876543210', 'website' => 'https://techcorp.com',
                'industry' => 'Information Technology', 'company_size' => '51-200',
                'founded_year' => 2015,
                'description' => 'Leading software development company specializing in enterprise solutions.',
                'city' => 'Ahmedabad', 'state' => 'Gujarat', 'country' => 'India', 'pincode' => '380001',
                'status' => 'active', 'verified_at' => $now, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        $companyId = DB::table('companies')->where('email', 'hr@techcorp.com')->value('id');

        // ── Job Posts ─────────────────────────────────────────────────────────
        $jobs = [
            [
                'company_id' => $companyId, 'job_category_id' => $itId,
                'title' => 'Senior Laravel Developer', 'slug' => 'senior-laravel-developer-001',
                'description' => 'Looking for experienced Laravel developer to join our backend team.',
                'requirements' => '5+ years PHP/Laravel, MySQL, REST APIs.',
                'job_type' => 'full-time', 'work_mode' => 'hybrid', 'experience_level' => 'senior',
                'experience_min_years' => 4, 'experience_max_years' => 8,
                'salary_min' => 800000, 'salary_max' => 1400000, 'salary_currency' => 'INR',
                'is_salary_visible' => true, 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'country' => 'India',
                'vacancies' => 2, 'status' => 'active', 'views_count' => 0,
                'published_at' => now()->subDays(3)->toDateTimeString(),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'company_id' => $companyId, 'job_category_id' => $itId,
                'title' => 'React.js Frontend Developer', 'slug' => 'reactjs-frontend-developer-001',
                'description' => 'Join our product team as a React developer building modern web apps.',
                'requirements' => '3+ years React.js, TypeScript, Tailwind CSS.',
                'job_type' => 'full-time', 'work_mode' => 'remote', 'experience_level' => 'mid',
                'experience_min_years' => 2, 'experience_max_years' => 5,
                'salary_min' => 600000, 'salary_max' => 1000000, 'salary_currency' => 'INR',
                'is_salary_visible' => true, 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'country' => 'India',
                'vacancies' => 3, 'status' => 'active', 'views_count' => 0,
                'published_at' => now()->subDays(1)->toDateTimeString(),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'company_id' => $companyId, 'job_category_id' => $mktId,
                'title' => 'Digital Marketing Manager', 'slug' => 'digital-marketing-manager-001',
                'description' => 'Lead our digital marketing team to drive growth and brand visibility.',
                'requirements' => '4+ years digital marketing, SEO, SEM, social media.',
                'job_type' => 'full-time', 'work_mode' => 'on-site', 'experience_level' => 'senior',
                'salary_min' => 700000, 'salary_max' => 1100000, 'salary_currency' => 'INR',
                'is_salary_visible' => true, 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'country' => 'India',
                'vacancies' => 1, 'status' => 'closed', 'views_count' => 0,
                'published_at' => now()->subDays(20)->toDateTimeString(),
                'closed_at'    => now()->subDays(5)->toDateTimeString(),
                'created_at' => $now, 'updated_at' => $now,
            ],
        ];

        foreach ($jobs as $job) {
            if (! DB::table('job_posts')->where('slug', $job['slug'])->exists()) {
                DB::table('job_posts')->insert($job);
            }
        }

        // ── Job Seeker User ───────────────────────────────────────────────────
        if (! DB::table('users')->where('email', 'seeker@gmail.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Rahul Sharma', 'email' => 'seeker@gmail.com',
                'password' => Hash::make('password'), 'role' => 'job_seeker',
                'email_verified_at' => $now, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        $seekerId = DB::table('users')->where('email', 'seeker@gmail.com')->value('id');

        // ── Job Seeker Profile ────────────────────────────────────────────────
        if (! DB::table('job_seeker_profiles')->where('user_id', $seekerId)->exists()) {
            DB::table('job_seeker_profiles')->insert([
                'user_id' => $seekerId, 'full_name' => 'Rahul Sharma',
                'phone' => '+91-9988776655', 'current_job_title' => 'PHP Developer',
                'current_company' => 'Freelance', 'total_experience_years' => 3,
                'skills' => 'PHP, Laravel, MySQL, Vue.js, REST API',
                'bio' => 'Passionate backend developer looking for full-time opportunities.',
                'city' => 'Ahmedabad', 'state' => 'Gujarat', 'country' => 'India',
                'is_actively_looking' => true, 'is_profile_visible' => true,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        $profileId = DB::table('job_seeker_profiles')->where('user_id', $seekerId)->value('id');

        // ── Education ─────────────────────────────────────────────────────────
        if (! DB::table('job_seeker_educations')->where('user_id', $seekerId)->exists()) {
            DB::table('job_seeker_educations')->insert([
                'user_id' => $seekerId, 'degree' => 'B.Tech',
                'institution' => 'Gujarat Technological University',
                'field' => 'Computer Science', 'start_year' => 2014,
                'end_year' => 2018, 'is_current' => false,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // ── Experience ────────────────────────────────────────────────────────
        if (! DB::table('job_seeker_experiences')->where('user_id', $seekerId)->exists()) {
            DB::table('job_seeker_experiences')->insert([
                'user_id' => $seekerId, 'job_title' => 'PHP Developer',
                'company' => 'Freelance Projects', 'location' => 'Ahmedabad, Gujarat',
                'start_date' => '2020-01-01', 'is_current' => true,
                'description' => 'Building web applications using Laravel and Vue.js.',
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // ── Sample Application ────────────────────────────────────────────────
        $jobId = DB::table('job_posts')->where('slug', 'senior-laravel-developer-001')->value('id');

        if ($jobId && ! DB::table('job_applications')->where('job_post_id', $jobId)->where('user_id', $seekerId)->exists()) {
            DB::table('job_applications')->insert([
                'job_post_id'           => $jobId,
                'user_id'               => $seekerId,
                'job_seeker_profile_id' => $profileId,
                'cover_letter'          => 'I am very interested in this position. My 3+ years of Laravel experience makes me a great fit.',
                'status'                => 'applied',
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);
        }

        $this->command->info('Job module demo data seeded.');
    }
}