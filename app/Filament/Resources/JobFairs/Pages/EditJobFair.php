<?php

namespace App\Filament\Resources\JobFairs\Pages;

use App\Filament\Resources\JobFairs\JobFairResource;
use App\Models\JobFair;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditJobFair extends EditRecord
{
    protected static string $resource = JobFairResource::class;

    protected ?bool $hasUnsavedDataChangesAlert = true;

    protected function afterSave(): void
    {
        /**
         * @var JobFair $record
         */
        $record = $this->record;
        $record->refreshDisplayName();
    }

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
