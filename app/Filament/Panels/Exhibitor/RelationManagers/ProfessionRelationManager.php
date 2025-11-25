<?php

namespace App\Filament\Panels\Exhibitor\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProfessionRelationManager extends RelationManager
{
    protected static string $relationship = 'professions';

    protected static ?string $title = 'Berufe';

    protected static ?string $modelLabel = 'Beruf';

    protected static ?string $pluralModelLabel = 'Berufe';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Toggle::make('offers_internship')
                    ->label('Praktikum möglich'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('display_name')
            ->columns([
                TextColumn::make('display_name')
                    ->label('Beruf')
                    ->searchable(),

                IconColumn::make('offers_internship')
                    ->label('Praktikum')
                    ->boolean(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->schema(fn (AttachAction $action) => [
                        $action->getRecordSelect(),

                        Toggle::make('offers_internship')
                            ->label('Praktikum möglich'),
                    ])
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
