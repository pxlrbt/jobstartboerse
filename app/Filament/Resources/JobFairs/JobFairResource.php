<?php

namespace App\Filament\Resources\JobFairs;

use App\Filament\Resources\Exhibitors\RelationManagers\SchoolRegistrationsRelationManager;
use App\Filament\Resources\JobFairs\Pages\CreateJobFair;
use App\Filament\Resources\JobFairs\Pages\EditJobFair;
use App\Filament\Resources\JobFairs\Pages\ListJobFairs;
use App\Filament\Resources\JobFairs\RelationManagers\ExhibitorsRelationManager;
use App\Filament\Resources\JobFairs\Schemas\JobFairForm;
use App\Filament\Resources\JobFairs\Tables\JobFairsTable;
use App\Models\JobFair;
use BackedEnum;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationManagerConfiguration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobFairResource extends Resource
{
    protected static ?string $model = JobFair::class;

    protected static string|BackedEnum|null $navigationIcon = Phosphor::CalendarDuotone;

    protected static ?string $recordTitleAttribute = 'display_name';

    protected static ?string $modelLabel = 'Börse';

    protected static ?string $pluralModelLabel = 'Börsen';

    public static function form(Schema $schema): Schema
    {
        return JobFairForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobFairsTable::configure($table);
    }

    /**
     * @return array<RelationManagerConfiguration>
     */
    public static function getRelations(): array
    {
        return [
            SchoolRegistrationsRelationManager::make(),
            ExhibitorsRelationManager::make(),
        ];
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListJobFairs::route('/'),
            'create' => CreateJobFair::route('/create'),
            'edit' => EditJobFair::route('/{record}/edit'),
        ];
    }

    /**
     * @return Builder<JobFair>
     */
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
