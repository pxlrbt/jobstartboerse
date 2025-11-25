<?php

namespace App\Models;

use Database\Factories\DegreeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Degree extends Model
{
    /**
     * @use HasFactory<DegreeFactory>
     */
    use HasFactory;

    use SoftDeletes;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = str()->slug($model->display_name);

            return $model;
        });
    }

    /**
     * @return BelongsToMany<Exhibitor, $this>
     */
    public function exhibitors(): BelongsToMany
    {
        return $this->belongsToMany(Exhibitor::class);
    }
}
