<?php

namespace App\Filament\Resources\Exhibitors\Pages;

use App\Filament\Resources\Exhibitors\ExhibitorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExhibitors extends ListRecords
{
    protected static string $resource = ExhibitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
