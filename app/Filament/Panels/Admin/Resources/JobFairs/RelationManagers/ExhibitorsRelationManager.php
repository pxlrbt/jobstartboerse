<?php

namespace App\Filament\Panels\Admin\Resources\JobFairs\RelationManagers;

use App\Filament\Columns\ContactPersonColumn;
use App\Filament\Columns\ExhibitorNameColumn;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ExhibitorsRelationManager extends RelationManager
{
    protected static string $relationship = 'exhibitors';

    protected static ?string $title = 'Aussteller';

    protected static ?string $modelLabel = 'Aussteller';

    protected static ?string $pluralModelLabel = 'Aussteller';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('stall_number')
                    ->label('Standnummer'),

                Toggle::make('needs_power')
                    ->label('Strom'),

                Textarea::make('internal_note')
                    ->label('Notiz')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('display_name')
            ->columns([
                ExhibitorNameColumn::make(),
                ContactPersonColumn::make(),

                TextColumn::make('stall_number')
                    ->label('Standnummer'),

                IconColumn::make('needs_power')
                    ->label('Strom')
                    ->boolean(),

                IconColumn::make('pivot.internal_note')
                    ->label('Notiz')
                    ->icon(fn ($state) => filled($state) ? Heroicon::Envelope : null)
                    ->action(
                        Action::make('show_note')
                            ->action(null)
                            ->modalWidth(Width::Medium)
                            ->modalHeading('Interne Notiz')
                            ->modalContent(fn ($record) => new HtmlString(nl2br($record->pivot_internal_note)))
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Schließen')
                    ),
            ])

            ->headerActions([
                AttachAction::make()
                    ->schema(fn (AttachAction $action) => [
                        $action->getRecordSelect(),

                        Toggle::make('pivot_needs_power')
                            ->label('Strom'),

                        Textarea::make('internal_note')
                            ->label('Notiz')
                            ->columnSpanFull(),
                    ])
                    ->preloadRecordSelect()
                    ->multiple(),
            ])
            ->recordActions([
                EditAction::make()
                    ->tooltip(__('filament-actions::edit.single.label'))
                    ->modalWidth(Width::Large)
                    ->iconButton(),
                DetachAction::make()
                    ->tooltip(__('filament-actions::detach.single.label'))
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
