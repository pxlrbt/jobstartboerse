<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Stadthalle', 'Messe', 'Hans-Thoma-Schule', 'Frizt-Boehle-Halle', 'Bürgerhaus']),
            'street' => $this->faker->streetName(),
            'zipcode' => $this->faker->postcode(),
            'city' => $this->faker->city(),
        ];
    }
}
