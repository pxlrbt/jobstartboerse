<?php

namespace Database\Factories;

use App\Models\Profession;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessionFactory extends Factory
{
    protected $model = Profession::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug(),
            'display_name' => $this->faker->jobTitle(),

            'has_internship' => $this->faker->boolean(),
            'has_apprenticeship' => $this->faker->boolean(),
            'has_degree' => $this->faker->boolean(),
        ];
    }
}
