<?php

namespace App\Filament\Panels\Admin\Resources\Degrees\Pages;

use App\Filament\Panels\Admin\Resources\Degrees\DegreeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDegrees extends ManageRecords
{
    protected static string $resource = DegreeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateDataUsing(function (array $data) {
                    $data['slug'] = str()->slug($data['display_name']);

                    return $data;
                }),
        ];
    }
}
