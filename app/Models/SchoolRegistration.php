<?php

namespace App\Models;

use App\DataObjects\SchoolRegistrationClass;
use Database\Factories\SchoolRegistrationFactory;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property Collection<int, SchoolRegistrationClass> $classes
 */
class SchoolRegistration extends Model
{
    /**
     * @use HasFactory<SchoolRegistrationFactory>
     */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return array{
     *     classes: 'AsCollection::of(SchoolRegistrationClass::class)'
     * }
     */
    protected function casts(): array
    {
        return [
            'classes' => AsCollection::of([SchoolRegistrationClass::class, 'fromArray']),
        ];
    }

    /**
     * @return BelongsTo<JobFair, $this>
     */
    public function jobFair(): BelongsTo
    {
        return $this->belongsTo(JobFair::class);
    }

    /**
     * @return Attribute<int, never>
     */
    protected function studentsCount(): Attribute
    {
        return Attribute::get(
            fn ($value, array $attributes) => $this->classes->sum('students_count') ?? 0,
        );
    }
}
