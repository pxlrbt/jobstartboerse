<?php

namespace App\Models;

use Database\Factories\MailingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mailing extends Model
{
    /** @use HasFactory<MailingFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return BelongsToMany<Exhibitor, $this>
     */
    public function exhibitors(): BelongsToMany
    {
        return $this->belongsToMany(Exhibitor::class, table: 'mailing_exhibitor')
            ->withPivot(['name', 'email'])
            ->withTimestamps();
    }
}
