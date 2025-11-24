<?php

namespace Database\Factories;

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveySubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyAnswer>
 */
class SurveyAnswerFactory extends Factory
{
    protected $model = SurveyAnswer::class;

    public function definition(): array
    {
        return [
            'survey_submission_id' => SurveySubmission::factory(),
            'survey_question_id' => SurveyQuestion::factory(),
            'content' => $this->faker->word(),
        ];
    }
}
