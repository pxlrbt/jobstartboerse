<?php

namespace App\Filament\Exhibitor\Resources\Surveys\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SurveysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name')
                    ->label('Umfrage')
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make()->label('Teilnehmen'),
            ]);
    }
}
