<?php

namespace Database\Factories;

use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Survey>
 */
class SurveyFactory extends Factory
{
    protected $model = Survey::class;

    public function definition(): array
    {
        return [
            'display_name' => $this->faker->sentence(),

            'starts_at' => $start = $this->faker->dateTimeThisYear(),
            'ends_at' => $this->faker->dateTimeThisYear($start),
        ];
    }
}
