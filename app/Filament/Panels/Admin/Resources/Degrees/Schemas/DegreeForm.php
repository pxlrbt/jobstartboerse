<?php

namespace App\Filament\Panels\Admin\Resources\Degrees\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DegreeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('display_name')
                    ->label('Name')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }
}
