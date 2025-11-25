<?php

namespace App\Filament\Panels\Admin\Resources\Users\Pages;

use App\Filament\Panels\Admin\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected Width|string|null $maxContentWidth = Width::ScreenMedium;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
