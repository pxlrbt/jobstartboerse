<?php

namespace App\Filament\Panels\Admin\Resources\JobFairs\Pages;

use App\Filament\Panels\Admin\Resources\JobFairs\JobFairResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditJobFair extends EditRecord
{
    protected static string $resource = JobFairResource::class;

    protected ?bool $hasUnsavedDataChangesAlert = true;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
