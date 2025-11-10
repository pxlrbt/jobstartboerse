<?php

namespace App\Filament\Resources;

use App\Filament\Enums\NavigationGroup;
use App\Filament\Resources\MailingResource\Pages;
use App\Models\Mailing;
use BackedEnum;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class MailingResource extends Resource
{
    protected static ?string $model = Mailing::class;

    protected static ?string $slug = 'mailings';

    protected static string|BackedEnum|null $navigationIcon = Phosphor::EnvelopeDuotone;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Functions;

    public static function form(Schema $schema): Schema
    {
        // $components = QueryBuilder::make('query')
        //     ->constraints([
        //         QueryBuilder\Constraints\SelectConstraint::make('job_fairs')
        //             ->label('Veranstaltungen')
        //             ->relationship('jobFairs', 'display_name')
        //             ->multiple(),
        //     ])
        //     ->getSchemaComponents();

        return $schema
            ->columns(1)
            ->components([
                // Section::make('Auswahl Empfänger')
                //     ->compact()
                //     ->components($components),

                TextInput::make('subject')
                    ->label('Betreff')
                    ->required(),

                RichEditor::make('message')
                    ->label('Nachricht')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->label('Betreff')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Versendet am')
                    ->date()
                    ->sortable(),

            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailings::route('/'),
            'create' => Pages\CreateMailing::route('/create'),
            'edit' => Pages\EditMailing::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
