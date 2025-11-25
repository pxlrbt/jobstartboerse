<?php

namespace App\Models;

use Database\Factories\SchoolRegistrationFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property Collection<int, SchoolRegistrationClass> $classes
 * @property int $studentsCount
 */
class SchoolRegistration extends Model
{
    /**
     * @use HasFactory<SchoolRegistrationFactory>
     */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return BelongsTo<JobFair, $this>
     */
    public function jobFair(): BelongsTo
    {
        return $this->belongsTo(JobFair::class);
    }

    /**
     * @return HasMany<SchoolRegistrationClass, $this>
     */
    public function classes(): HasMany
    {
        return $this->hasMany(SchoolRegistrationClass::class);
    }

    /**
     * @return Attribute<int, never>
     */
    protected function studentsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->classes->sum('students_count')
        );
    }
}
