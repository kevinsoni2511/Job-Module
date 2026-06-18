<?php

namespace Database\Factories;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'job_post_id'  => JobPost::factory(),
            'user_id'      => User::factory()->jobSeeker(),
            'cover_letter' => fake()->paragraph(3),
            'status'       => fake()->randomElement(['applied', 'under_review', 'shortlisted', 'rejected', 'hired']),
        ];
    }

    public function shortlisted(): static { return $this->state(['status' => 'shortlisted']); }
    public function rejected(): static    { return $this->state(['status' => 'rejected']); }
    public function hired(): static       { return $this->state(['status' => 'hired']); }
}