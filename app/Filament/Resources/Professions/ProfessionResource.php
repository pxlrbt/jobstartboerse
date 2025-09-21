<?php

namespace App\Filament\Resources\Professions;

use App\Filament\Enums\NavigationGroup;
use App\Filament\Resources\Professions\Pages\ManageProfessions;
use App\Filament\Resources\Professions\Schemas\ProfessionForm;
use App\Filament\Resources\Professions\Tables\ProfessionsTable;
use App\Models\Profession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Data;

    protected static ?string $recordTitleAttribute = 'display_name';

    public static function form(Schema $schema): Schema
    {
        return ProfessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfessionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageProfessions::route('/'),
        ];
    }

    /**
     * @return Builder<Profession>
     */
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
