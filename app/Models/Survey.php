<?php

namespace App\Models;

use Database\Factories\SurveyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
    /**
     * @use HasFactory<SurveyFactory>
     */
    use HasFactory;

    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsToMany<JobFair, $this>
     */
    public function jobFairs(): BelongsToMany
    {
        return $this
            ->belongsToMany(JobFair::class, 'survey_job_fair', 'survey_id', 'job_fair_id')
            ->withTimestamps();
    }

    /**
     * @return HasMany<SurveyQuestion, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    /**
     * @return HasMany<SurveySubmission, $this>
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(SurveySubmission::class);
    }
}
