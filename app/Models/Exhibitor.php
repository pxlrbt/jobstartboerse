<?php

namespace App\Models;

use App\Enums\Branch;
use Database\Factories\ExhibitorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exhibitor extends Model
{
    /**
     * @use HasFactory<ExhibitorFactory>
     */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'branch' => Branch::class,
        ];
    }

    /**
     * @return BelongsTo<Address, $this>
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    /**
     * @return BelongsTo<Address, $this>
     */
    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    /**
     * @return BelongsTo<ContactPerson, $this>
     */
    public function contactPerson(): BelongsTo
    {
        return $this->belongsTo(ContactPerson::class, 'contact_person_id');
    }

    /**
     * @return BelongsTo<ContactPerson, $this>
     */
    public function billingContactPerson(): BelongsTo
    {
        return $this->belongsTo(ContactPerson::class, 'billing_contact_person_id');
    }
}
