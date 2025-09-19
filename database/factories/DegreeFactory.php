<?php

namespace Database\Factories;

use App\Models\Degree;
use Illuminate\Database\Eloquent\Factories\Factory;

class DegreeFactory extends Factory
{
    protected $model = Degree::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug(),
            'display_name' => $this->faker->jobTitle(),
        ];
    }
}
