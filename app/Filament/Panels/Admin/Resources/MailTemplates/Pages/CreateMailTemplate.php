<?php

namespace App\Filament\Panels\Admin\Resources\MailTemplates\Pages;

use App\Filament\Panels\Admin\Resources\MailTemplates\MailTemplateResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateMailTemplate extends CreateRecord
{
    protected static string $resource = MailTemplateResource::class;

    protected Width|string|null $maxContentWidth = Width::ScreenMedium;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
