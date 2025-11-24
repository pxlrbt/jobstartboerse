<?php

namespace App\Models;

use App\Enums\SurveyQuestionType;
use Database\Factories\SurveyQuestionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    protected function textContent(): Attribute
    {
        return Attribute::get(function ($value, array $attributes) {
            return match ($this->question->type) {
                SurveyQuestionType::Toggle => $this->content ? 'Ja' : 'Nein',
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
