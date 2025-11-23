<?php

namespace Database\Factories;

use App\Enums\SurveyQuestionType;
use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class SurveyQuestionFactory extends Factory
{
    protected $model = SurveyQuestion::class;

    public function configure(): self
    {
        return $this->afterMaking(function (SurveyQuestion $question) {
            if ($question->type->hasOptions()) {
                $question->options = collect($this->faker->words());
            }
        });
    }

    public function definition(): array
    {
        return [
            'display_name' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(SurveyQuestionType::cases()),
        ];
    }
}
