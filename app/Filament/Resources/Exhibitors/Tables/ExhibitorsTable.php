<?php

namespace App\Filament\Resources\Exhibitors\Tables;

use App\Models\Exhibitor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ExhibitorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_file')
                    ->label(''),

                TextColumn::make('display_name')
                    ->formatStateUsing(function (Exhibitor $record) {
                        return new HtmlString(<<<HTML
                            {$record->display_name}<br>
                            <small>{$record->display_name_affix}</small>
                        HTML);
                    })
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('contactPerson.last_name')
                    ->label('Ansprechpartner')
                    ->formatStateUsing(function (Exhibitor $record) {
                        return new HtmlString(<<<HTML
                            <div style="line-height: 1.2">
                                {$record->contactPerson->last_name}<br>
                                <small>
                                    <a href="mailto:{$record->contactPerson->email}">
                                        {$record->contactPerson->email}
                                    </a>
                                </small>
                            </div>
                        HTML);
                    })
                    ->searchable()
                    ->sortable(),
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
