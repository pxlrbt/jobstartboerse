<?php

namespace App\Filament\Panels\Admin\Resources\Users\Pages;

use App\Filament\Panels\Admin\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
