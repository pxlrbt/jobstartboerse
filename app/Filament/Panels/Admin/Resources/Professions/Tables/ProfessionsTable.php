<?php

namespace App\Filament\Panels\Admin\Resources\Professions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProfessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('display_name')
            ->columns([
                TextColumn::make('display_name')
                    ->label('Beruf')
                    ->searchable(),

                IconColumn::make('has_internship')
                    ->label('Praktikum')
                    ->boolean(),

                IconColumn::make('has_apprenticeship')
                    ->label('Ausbildung')
                    ->boolean(),

                IconColumn::make('has_degree')
                    ->label('Ausbildung')
                    ->boolean(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
