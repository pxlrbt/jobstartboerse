<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobFair>
 */
class JobFairFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'display_name' => $this->faker->city().' • '.date('Y'),
            'description' => $this->faker->realText(),
            'is_public' => $this->faker->boolean(),
            'are_exhibitors_public' => $this->faker->boolean(),
            'lounge_registration_ends_at' => $this->faker->optional(.3)->dateTimeBetween('now', '+ 6 months'),
        ];
    }
}
