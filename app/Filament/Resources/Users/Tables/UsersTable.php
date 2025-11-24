<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\Role;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use STS\FilamentImpersonate\Actions\Impersonate;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('exhibitor.display_name')
                    ->label('Aussteller')
                    ->searchable(),

                TextColumn::make('role')
                    ->label('Rolle')
                    ->badge()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Rolle')
                    ->options(Role::toOptions()),
            ])
            ->recordActions([
                EditAction::make(),
                Impersonate::make()
                    ->redirectTo(Filament::getPanel('exhibitor')->getUrl()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
