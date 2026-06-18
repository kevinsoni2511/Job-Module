<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->randomElement([
            'Senior Laravel Developer', 'React Frontend Developer', 'UI/UX Designer',
            'DevOps Engineer', 'Data Analyst', 'Product Manager', 'HR Manager',
            'Digital Marketing Specialist', 'iOS Developer', 'Android Developer',
        ]);

        return [
            'company_id'           => Company::factory(),
            'job_category_id'      => JobCategory::inRandomOrder()->value('id'),
            'title'                => $title,
            'slug'                 => strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $title), '-')) . '-' . substr(md5(uniqid()), 0, 6),
            'description'          => fake()->paragraphs(3, true),
            'requirements'         => fake()->paragraphs(2, true),
            'responsibilities'     => fake()->paragraphs(2, true),
            'benefits'             => fake()->paragraph(),
            'job_type'             => fake()->randomElement(['full-time', 'part-time', 'contract', 'internship']),
            'work_mode'            => fake()->randomElement(['remote', 'on-site', 'hybrid']),
            'experience_level'     => fake()->randomElement(['entry', 'mid', 'senior']),
            'experience_min_years' => fake()->numberBetween(0, 3),
            'experience_max_years' => fake()->numberBetween(4, 10),
            'salary_min'           => fake()->numberBetween(300000, 800000),
            'salary_max'           => fake()->numberBetween(900000, 2000000),
            'salary_currency'      => 'INR',
            'is_salary_visible'    => true,
            'city'                 => fake()->city(),
            'state'                => fake()->state(),
            'country'              => 'India',
            'vacancies'            => fake()->numberBetween(1, 5),
            'application_deadline' => now()->addDays(fake()->numberBetween(15, 60)),
            'status'               => 'active',
            'published_at'         => now()->subDays(fake()->numberBetween(1, 10)),
            'views_count'          => fake()->numberBetween(0, 500),
        ];
    }

    public function closed(): static
    {
        return $this->state(['status' => 'closed', 'closed_at' => now()]);
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft', 'published_at' => null]);
    }
}