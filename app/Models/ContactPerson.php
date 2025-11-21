<?php

namespace App\Models;

use Database\Factories\ContactPersonFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactPerson extends Model
{
    /**
     * @use HasFactory<ContactPersonFactory>
     */
    use HasFactory;

    /**
     * @return BelongsTo<Exhibitor, $this>
     */
    public function exhibitor(): BelongsTo
    {
        return $this->belongsTo(Exhibitor::class);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::get(
            get: fn ($value, array $attributes) => $this->first_name.' '.$this->last_name,
        );
    }
}
