<?php

namespace App\Models;

use App\DataObjects\Attachment;
use App\Models\Pivot\ExhibitorRegistration;
use Database\Factories\JobFairFactory;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobFair extends Model
{
    /** @use HasFactory<JobFairFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return array{
     *     attachments: 'AsCollection::of(Attachment::class)',
     *     lounge_files_students: 'array',
     *     lounge_files_exhibitors: 'array'
     * }
     */
    protected function casts(): array
    {
        return [
            'attachments' => AsCollection::of([Attachment::class, 'fromArray']),
            'lounge_files_students' => 'array',
            'lounge_files_exhibitors' => 'array',
        ];
    }

    /**
     * @return HasMany<JobFairDate, $this>
     */
    public function dates(): HasMany
    {
        return $this->hasMany(JobFairDate::class);
    }

    /**
     * @return BelongsToMany<Location, $this>
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class);
    }

    /**
     * @return BelongsToMany<Exhibitor, $this, ExhibitorRegistration>
     */
    public function exhibitors(): BelongsToMany
    {
        return $this->belongsToMany(Exhibitor::class)
            ->using(ExhibitorRegistration::class)
            ->withPivot([
                'stall_number',
                'needs_power',
                'internal_note',
            ]);
    }

    /**
     * @return Collection<int, Profession>
     */
    public function professions(): Collection
    {
        return Profession::query()
            ->whereHas('exhibitors', fn ($query) => $query
                ->whereIn('exhibitors.id', fn ($query) => $query
                    ->select('exhibitor_job_fair.exhibitor_id')
                    ->from('exhibitor_job_fair')
                    ->where('exhibitor_job_fair.job_fair_id', $this->id)
                ))
            ->get();
    }

    /**
     * @return Collection<int, Degree>
     */
    public function degrees(): Collection
    {
        return Degree::query()
            ->whereHas('exhibitors', fn ($query) => $query
                ->whereIn('exhibitors.id', fn ($query) => $query
                    ->select('exhibitor_job_fair.exhibitor_id')
                    ->from('exhibitor_job_fair')
                    ->where('exhibitor_job_fair.job_fair_id', $this->id)
                ))
            ->get();
    }

    /**
     * @return HasMany<LoungeParticipation, $this>
     */
    public function loungeParticipations(): HasMany
    {
        return $this->hasMany(LoungeParticipation::class);
    }

    /**
     * @return HasMany<SchoolRegistration, $this>
     */
    public function schoolRegistrations(): HasMany
    {
        return $this->hasMany(SchoolRegistration::class);
    }

    public function refreshDisplayName(): void
    {
        $location = $this->locations()->first();
        $date = $this->dates()->first();

        $this->update(['display_name' => "{$location->city} • {$date->date->format('Y')}"]);
    }
}
