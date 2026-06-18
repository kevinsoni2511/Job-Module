<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();
        return [
            'user_id'      => User::factory()->jobKeeper(),
            'name'         => $name,
            'slug'         => strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $name), '-')) . '-' . substr(md5(uniqid()), 0, 5),
            'email'        => fake()->unique()->companyEmail(),
            'phone'        => fake()->phoneNumber(),
            'website'      => fake()->url(),
            'industry'     => fake()->randomElement(['Information Technology', 'Finance', 'Healthcare', 'Education', 'Marketing']),
            'company_size' => fake()->randomElement(['1-10', '11-50', '51-200', '201-500', '500+']),
            'founded_year' => fake()->numberBetween(2000, 2022),
            'description'  => fake()->paragraph(3),
            'city'         => fake()->city(),
            'state'        => fake()->state(),
            'country'      => 'India',
            'pincode'      => fake()->postcode(),
            'status'       => 'active',
            'verified_at'  => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending', 'verified_at' => null]);
    }
}