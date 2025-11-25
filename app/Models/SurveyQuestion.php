<?php

namespace App\Models;

use App\Enums\SurveyQuestionType;
use Database\Factories\SurveyQuestionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property SurveyQuestionType $type
 * @property Collection<int, string> $options
 */
class SurveyQuestion extends Model
{
    /**
     * @use HasFactory<SurveyQuestionFactory>
     */
    use HasFactory;

    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'type' => SurveyQuestionType::class,
            'options' => 'collection',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<SurveyAnswer, $this>
     */
    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
