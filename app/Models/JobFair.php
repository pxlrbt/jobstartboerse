<?php

namespace App\Models;

use App\DataObjects\Attachment;
use Database\Factories\JobFairFactory;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobFair extends Model
{
    /** @use HasFactory<JobFairFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return array{
     *     attachments: 'AsCollection::of(Attachment::class)',
     *     lounge_files_students: 'array',
     *     lounge_files_exhibitors: 'array'
     * }
     */
    protected function casts(): array
    {
        return [
            'attachments' => AsCollection::of([Attachment::class, 'fromArray']),
            'lounge_files_students' => 'array',
            'lounge_files_exhibitors' => 'array',
        ];
    }

    /**
     * @return Attribute<non-falsy-string,never>
     */
    protected function displayName(): Attribute
    {
        return Attribute::get(
            fn ($value, array $attributes) => $this->locations->first()?->city.' ⋅ '.$this->dates->first()?->date->format('Y')
        );
    }

    /**
     * @return HasMany<JobFairDate,$this>
     */
    public function dates(): HasMany
    {
        return $this->hasMany(JobFairDate::class);
    }

    /**
     * @return BelongsToMany<Location,$this>
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class);
    }

    public function schoolRegistrations(): HasMany
    {
        return $this->hasMany(SchoolRegistration::class);
    }
}
