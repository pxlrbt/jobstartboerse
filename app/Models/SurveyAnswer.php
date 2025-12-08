<?php

namespace App\Models;

use App\Enums\SurveyQuestionType;
use Database\Factories\SurveyQuestionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property SurveyQuestion $question
 * @property array<string, mixed>|string|null $content
 */
class SurveyAnswer extends Model
{
    /**
     * @use HasFactory<SurveyQuestionFactory>
     */
    use HasFactory;

    use SoftDeletes;

    protected function casts(): array
    {
        return [
            // 'question_type' => SurveyQuestionType::class,
            'content' => 'json',
        ];
    }

    /**
     * @return Attribute<mixed, mixed>
     */
    protected function textContent(): Attribute
    {
        return Attribute::get(function ($value, array $attributes) {
            /** @var SurveyQuestionType $questionType */
            $questionType = $this->question->type;

            return match ($questionType) {
                SurveyQuestionType::Toggle => $this->content ? 'Ja' : 'Nein',
                SurveyQuestionType::Rating => match ((int) $this->content) {
                    1 => 'Sehr Gut',
                    2 => 'Gut',
                    3 => 'Zufriedenstellend',
                    4 => 'Nicht zufriedenstellend',
                },
                default => $this->content
            };
        });
    }

    /**
     * @return BelongsTo<SurveyQuestion, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }

    /**
     * @return BelongsTo<SurveySubmission, $this>
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(SurveySubmission::class, 'survey_submission_id');
    }
}
