<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'role'              => fake()->randomElement(['job_keeper', 'job_seeker']),
        ];
    }

    public function jobKeeper(): static
    {
        return $this->state(['role' => 'job_keeper']);
    }

    public function jobSeeker(): static
    {
        return $this->state(['role' => 'job_seeker']);
    }

    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }
}