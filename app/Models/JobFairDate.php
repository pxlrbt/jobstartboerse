<?php

namespace App\Models;

use Database\Factories\JobFairDateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobFairDate extends Model
{
    /** @use HasFactory<JobFairDateFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return array{
     *     date: 'date:Y-m-d'
     * }
     */
    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
        ];
    }

    /**
     * @return BelongsTo<JobFair,$this>
     */
    public function jobFair(): BelongsTo
    {
        return $this->belongsTo(JobFair::class, 'job_fair_id');
    }
}
