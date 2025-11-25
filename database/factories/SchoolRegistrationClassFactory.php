<?php

namespace Database\Factories;

use App\Models\SchoolRegistration;
use App\Models\SchoolRegistrationClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolRegistrationClass>
 */
class SchoolRegistrationClassFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_registration_id' => SchoolRegistration::factory(),
            'name' => 'Klasse '.$this->faker->numberBetween(5, 12).$this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'time' => $this->faker->time('H:i').' Uhr',
            'students_count' => $this->faker->numberBetween(10, 30),
        ];
    }
}
