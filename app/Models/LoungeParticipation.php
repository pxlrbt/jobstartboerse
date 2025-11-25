<?php

namespace App\Models;

use Database\Factories\LoungeParticipationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LoungeParticipation extends Model
{
    /**
     * @use HasFactory<LoungeParticipationFactory>
     */
    use HasFactory;

    protected $table = 'lounge_participations';

    /**
     * @return BelongsTo<JobFair, $this>
     */
    public function jobFair(): BelongsTo
    {
        return $this->belongsTo(JobFair::class);
    }

    /**
     * @return BelongsTo<Exhibitor, $this>
     */
    public function exhibitor(): BelongsTo
    {
        return $this->belongsTo(Exhibitor::class);
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
