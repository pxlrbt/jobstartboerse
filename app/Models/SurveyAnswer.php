<?php

namespace App\Models;

use App\Enums\SurveyQuestionType;
use Database\Factories\SurveyQuestionFactory;
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
            'question_type' => SurveyQuestionType::class,
            'content' => 'json',
        ];
    }

    /**
     * @return BelongsTo<SurveyQuestion, $this>
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    /**
     * @return BelongsTo<SurveySubmission, $this>
     */
    public function result(): BelongsTo
    {
        return $this->belongsTo(SurveySubmission::class);
    }
}
