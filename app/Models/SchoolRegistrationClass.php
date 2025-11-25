<?php

namespace App\Models;

use Database\Factories\SchoolRegistrationClassFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolRegistrationClass extends Model
{
    /**
     * @use HasFactory<SchoolRegistrationClassFactory>
     */
    use HasFactory;

    /**
     * @return BelongsTo<SchoolRegistration, $this>
     */
    public function schoolRegistration(): BelongsTo
    {
        return $this->belongsTo(SchoolRegistration::class);
    }
}
