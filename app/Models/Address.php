<?php

namespace App\Models;

use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    /**
     * @use HasFactory<AddressFactory>
     */
    use HasFactory;

    public function exhibitor(): BelongsTo
    {
        return $this->belongsTo(Exhibitor::class);
    }
}
