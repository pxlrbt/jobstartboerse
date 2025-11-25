<?php

namespace App\Filament\Panels\Admin\Resources\Users\Pages;

use App\Filament\Panels\Admin\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected Width|string|null $maxContentWidth = Width::ScreenMedium;
}
