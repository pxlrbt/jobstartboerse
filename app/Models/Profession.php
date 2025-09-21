<?php

namespace App\Models;

use Database\Factories\ProfessionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profession extends Model
{
    /**
     * @use HasFactory<ProfessionFactory>
     */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return array{
     *     has_internship: 'boolean',
     *     has_apprenticeship: 'boolean',
     *     has_degree: 'boolean'
     * }
     */
    protected function casts(): array
    {
        return [
            'has_internship' => 'boolean',
            'has_apprenticeship' => 'boolean',
            'has_degree' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<Exhibitor, $this>
     */
    public function exhibitors(): BelongsToMany
    {
        return $this->belongsToMany(Exhibitor::class);
    }
}
