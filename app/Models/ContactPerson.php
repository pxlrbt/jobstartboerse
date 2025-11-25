<?php

namespace App\Models;

use Database\Factories\ContactPersonFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    /**
     * @use HasFactory<ContactPersonFactory>
     */
    use HasFactory;

    /**
     * @return Attribute<non-falsy-string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::get(
            get: fn ($value, array $attributes) => $this->first_name.' '.$this->last_name,
        );
    }
}
