<?php

namespace App\Filament\Panels\Admin\Resources\MailTemplates\Pages;

use App\Filament\Panels\Admin\Resources\MailTemplates\MailTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditMailTemplate extends EditRecord
{
    protected static string $resource = MailTemplateResource::class;

    protected Width|string|null $maxContentWidth = Width::ScreenMedium;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
