<?php

namespace Database\Factories;

use App\DataObjects\SchoolRegistrationClass;
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

        $classes = [];

        for ($i = 0; $i < random_int(1, 3); $i++) {
            $classes[] = new SchoolRegistrationClass(
                name: $this->faker->numberBetween(5, 12).$this->faker->randomElement(['a', 'b', 'c', 'd', 'e']),
                time: $this->faker->time('H:i').' Uhr',
                students_count: $this->faker->numberBetween(10, 30),
            );
        }

        return [
            'school_name' => $schoolType.' '.$city,
            'school_type' => $schoolType,
            'school_city' => $city,
            'school_zipcode' => $this->faker->postcode(),

            'teacher' => $this->faker->firstName().' '.$this->faker->lastName(),
            'teacher_email' => $this->faker->unique()->safeEmail(),
            'teacher_phone' => $this->faker->phoneNumber(),

            'classes' => $classes,
        ];
    }
}
