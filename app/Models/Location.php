<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    use SoftDeletes;

    public function jobFairs(): BelongsToMany
    {
        return $this->belongsToMany(JobFair::class);
    }

    protected function displayName(): Attribute
    {
        return Attribute::get(
            fn ($value, array $attributes) => implode(', ', array_filter([$this->name, $this->city])),
        );
    }
}
