<?php

namespace App\Models;

use App\Enums\Branch;
use App\Models\Pivot\ExhibitorRegistration;
use Database\Factories\ExhibitorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exhibitor extends Model
{
    /**
     * @use HasFactory<ExhibitorFactory>
     */
    use HasFactory;

    use SoftDeletes;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = str()->slug($model->display_name);

            return $model;
        });
    }

    /**
     * @return array{
     *     branch: 'App\Enums\Branch'
     * }
     */
    protected function casts(): array
    {
        return [
            'branch' => Branch::class,
        ];
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
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

    /**
     * @return BelongsTo<ContactPerson, $this>
     */
    public function loungeContactPerson(): BelongsTo
    {
        return $this->belongsTo(ContactPerson::class, 'lounge_contact_person_id');
    }

    /**
     * @return BelongsToMany<Profession, $this>
     */
    public function professions(): BelongsToMany
    {
        return $this
            ->belongsToMany(Profession::class)
            ->withPivot(['offers_internship']);
    }

    /**
     * @return BelongsToMany<Degree, $this>
     */
    public function degrees(): BelongsToMany
    {
        return $this
            ->belongsToMany(Degree::class);
    }

    /**
     * @return BelongsToMany<JobFair, $this, ExhibitorRegistration>
     */
    public function jobFairs(): BelongsToMany
    {
        return $this->belongsToMany(JobFair::class)
            ->using(ExhibitorRegistration::class)
            ->withPivot([
                'stall_number',
                'needs_power',
                'internal_note',
            ]);
    }

    /**
     * @return BelongsToMany<Mailing, $this>
     */
    public function mailings(): BelongsToMany
    {
        return $this->belongsToMany(Mailing::class, table: 'mailing_exhibitor')
            ->withPivot(['name', 'email'])
            ->withTimestamps();
    }
}
