<?php

namespace App\Filament\Panels\Admin\Resources\Exhibitors\Tables;

use App\Filament\Columns\ContactPersonColumn;
use App\Filament\Columns\ExhibitorNameColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class ExhibitorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_file')
                    ->label(''),

                ExhibitorNameColumn::make(),
                ContactPersonColumn::make(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
