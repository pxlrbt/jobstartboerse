<?php

namespace App\Filament\Resources\Exhibitors\Pages;

use App\Filament\Resources\Exhibitors\ExhibitorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExhibitor extends EditRecord
{
    protected static string $resource = ExhibitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
