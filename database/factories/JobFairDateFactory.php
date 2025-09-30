<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobFairDate>
 */
class JobFairDateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => today()->addDays(random_int(1, 365)),

            'starts_at' => $this->faker->time('H:i'),
            'ends_at' => $this->faker->time('H:i'),
        ];
    }

    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => today()->subDays(random_int(10, 365)),
        ]);
    }
}
