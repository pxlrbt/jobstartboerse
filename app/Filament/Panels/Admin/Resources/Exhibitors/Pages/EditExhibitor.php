<?php

namespace App\Filament\Panels\Admin\Resources\Exhibitors\Pages;

use App\Filament\Panels\Admin\Resources\Exhibitors\ExhibitorResource;
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

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
