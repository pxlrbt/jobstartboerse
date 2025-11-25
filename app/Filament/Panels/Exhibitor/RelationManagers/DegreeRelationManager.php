<?php

namespace App\Filament\Panels\Exhibitor\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DegreeRelationManager extends RelationManager
{
    protected static string $relationship = 'degrees';

    protected static ?string $title = 'Studiengänge';

    protected static ?string $modelLabel = 'Studiengang';

    protected static ?string $pluralModelLabel = 'Studiengänge';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('display_name')
            ->columns([
                TextColumn::make('display_name')
                    ->label('Studiengang')
                    ->searchable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->multiple(),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalWidth(Width::Large)
                    ->iconButton(),
                DetachAction::make()
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
