<?php

namespace Database\Factories;

use App\Models\Exhibitor;
use App\Models\Survey;
use App\Models\SurveySubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveySubmission>
 */
class SurveySubmissionFactory extends Factory
{
    protected $model = SurveySubmission::class;

    public function definition(): array
    {
        return [
            'survey_id' => Survey::factory(),
            'exhibitor_id' => Exhibitor::factory(),
        ];
    }
}
