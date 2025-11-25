<?php

namespace App\Filament\Panels\Admin\Resources\Professions\Pages;

use App\Filament\Panels\Admin\Resources\Professions\ProfessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;

class ManageProfessions extends ManageRecords
{
    protected static string $resource = ProfessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->modalWidth(Width::Large),
        ];
    }
}
