<?php

namespace App\Filament\Panels\Admin\Resources\Exhibitors;

use App\Filament\Panels\Admin\Resources\Exhibitors\Pages\CreateExhibitor;
use App\Filament\Panels\Admin\Resources\Exhibitors\Pages\EditExhibitor;
use App\Filament\Panels\Admin\Resources\Exhibitors\Pages\ListExhibitors;
use App\Filament\Panels\Admin\Resources\Exhibitors\RelationManagers\DegreesRelationManager;
use App\Filament\Panels\Admin\Resources\Exhibitors\RelationManagers\JobFairsRelationManager;
use App\Filament\Panels\Admin\Resources\Exhibitors\RelationManagers\ProfessionsRelationManager;
use App\Filament\Panels\Admin\Resources\Exhibitors\RelationManagers\UsersRelationManager;
use App\Filament\Panels\Admin\Resources\Exhibitors\Schemas\ExhibitorForm;
use App\Filament\Panels\Admin\Resources\Exhibitors\Tables\ExhibitorsTable;
use App\Models\Exhibitor;
use BackedEnum;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExhibitorResource extends Resource
{
    protected static ?string $model = Exhibitor::class;

    protected static string|BackedEnum|null $navigationIcon = Phosphor::BuildingsDuotone;

    protected static ?string $recordTitleAttribute = 'display_name';

    protected static ?string $modelLabel = 'Aussteller';

    protected static ?string $pluralModelLabel = 'Aussteller';

    public static function form(Schema $schema): Schema
    {
        return ExhibitorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExhibitorsTable::configure($table);
    }

    /**
     * @return array<RelationManagerConfiguration>
     */
    public static function getRelations(): array
    {
        return [
            JobFairsRelationManager::make(),
            ProfessionsRelationManager::make(),
            DegreesRelationManager::make(),
            UsersRelationManager::make(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListExhibitors::route('/'),
            'create' => CreateExhibitor::route('/create'),
            'edit' => EditExhibitor::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(SoftDeletingScope::class);
    }
}
