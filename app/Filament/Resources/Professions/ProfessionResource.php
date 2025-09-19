<?php

namespace App\Filament\Resources\Professions;

use App\Filament\Enums\NavigationGroups;
use App\Filament\Resources\Professions\Pages\ManageProfessions;
use App\Models\Profession;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ProfessionResource extends Resource
{
    protected static ?string $model = Profession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;

    protected static ?string $modelLabel = 'Beruf';

    protected static ?string $pluralModelLabel = 'Berufe';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroups::Data;

    protected static ?string $recordTitleAttribute = 'display_name';

    public static function form(Schema $schema): Schema
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

    public static function table(Table $table): Table
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

    public static function getPages(): array
    {
        return [
            'index' => ManageProfessions::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
