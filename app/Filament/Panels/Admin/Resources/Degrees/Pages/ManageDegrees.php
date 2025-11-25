<?php

namespace App\Filament\Panels\Admin\Resources\Degrees\Pages;

use App\Filament\Panels\Admin\Resources\Degrees\DegreeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;

class ManageDegrees extends ManageRecords
{
    protected static string $resource = DegreeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modalWidth(Width::Large),
        ];
    }
}
