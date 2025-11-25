<?php

namespace App\Filament\Panels\Admin\Resources\JobFairs\Pages;

use App\Filament\Panels\Admin\Resources\JobFairs\JobFairResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobFair extends CreateRecord
{
    protected static string $resource = JobFairResource::class;

    protected ?bool $hasUnsavedDataChangesAlert = true;
}
