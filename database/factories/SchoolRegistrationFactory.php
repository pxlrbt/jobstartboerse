<?php

namespace Database\Factories;

use App\Models\SchoolRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolRegistration>
 */
class SchoolRegistrationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $city = $this->faker->city();
        $schoolType = $this->faker->randomElement(['Werkrealschule', 'Realschule', 'Gymnasium', 'Schulzentrum']);

        return [
            'school_name' => $schoolType.' '.$city,
            'school_type' => $schoolType,
            'school_city' => $city,
            'school_zipcode' => $this->faker->postcode(),

            'teacher' => $this->faker->firstName().' '.$this->faker->lastName(),
            'teacher_email' => $this->faker->unique()->safeEmail(),
            'teacher_phone' => $this->faker->phoneNumber(),
        ];
    }
}
