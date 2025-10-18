<?php

namespace App\Filament\Resources\JobFairs\Pages;

use App\Filament\Resources\JobFairs\JobFairResource;
use App\Models\JobFair;
use Filament\Resources\Pages\CreateRecord;

class CreateJobFair extends CreateRecord
{
    protected static string $resource = JobFairResource::class;

    protected ?bool $hasUnsavedDataChangesAlert = true;

    protected function afterCreate(): void
    {
        /**
         * @var JobFair $record
         */
        $record = $this->record;
        $record->refreshDisplayName();
    }
}
