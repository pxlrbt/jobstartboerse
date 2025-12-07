<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExhibitorRegistration extends Pivot
{
    protected $table = 'exhibitor_job_fair';

    protected function casts(): array
    {
        return [
            'needs_power' => 'boolean',
        ];
    }

    /**
     * @return Attribute<bool, never>
     */
    protected function isConfirmed(): Attribute
    {
        return Attribute::get(
            get: fn ($value, array $attributes) => $this->confirmed_at !== null,
        );
    }
}
