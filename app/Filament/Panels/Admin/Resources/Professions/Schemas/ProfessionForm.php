<?php

namespace App\Filament\Panels\Admin\Resources\Professions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProfessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('display_name')
                    ->label('Titel')
                    ->columnSpanFull()
                    ->required(),

                Toggle::make('has_internship')
                    ->label('Praktikum')
                    ->required(),

                Toggle::make('has_apprenticeship')
                    ->label('Ausbildung')
                    ->required(),

                Toggle::make('has_degree')
                    ->label('Studium')
                    ->required(),
            ]);
    }
}
