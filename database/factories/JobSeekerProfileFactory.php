<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobSeekerProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'                => User::factory()->jobSeeker(),
            'full_name'              => fake()->name(),
            'phone'                  => fake()->phoneNumber(),
            'date_of_birth'          => fake()->dateTimeBetween('-40 years', '-18 years')->format('Y-m-d'),
            'gender'                 => fake()->randomElement(['male', 'female', 'other']),
            'current_job_title'      => fake()->jobTitle(),
            'current_company'        => fake()->company(),
            'total_experience_years' => fake()->numberBetween(0, 15),
            'current_salary'         => fake()->numberBetween(300000, 1500000),
            'expected_salary'        => fake()->numberBetween(400000, 2000000),
            'bio'                    => fake()->paragraph(2),
            'skills'                 => 'PHP, Laravel, MySQL, JavaScript, REST API',
            'city'                   => fake()->city(),
            'state'                  => fake()->state(),
            'country'                => 'India',
            'linkedin_url'           => 'https://linkedin.com/in/' . fake()->userName(),
            'is_actively_looking'    => true,
            'is_profile_visible'     => true,
        ];
    }
}