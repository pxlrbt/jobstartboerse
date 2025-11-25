<?php

namespace App\Filament\Panels\Admin\Resources\MailTemplates\Pages;

use App\Filament\Panels\Admin\Resources\MailTemplates\MailTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMailTemplates extends ListRecords
{
    protected static string $resource = MailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
