<?php

namespace App\Filament\Resources\Degrees;

use App\Filament\Enums\NavigationGroup;
use App\Filament\Resources\Degrees\Pages\ManageDegrees;
use App\Filament\Resources\Degrees\Schemas\DegreeForm;
use App\Filament\Resources\Degrees\Tables\DegreesTable;
use App\Models\Degree;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DegreeResource extends Resource
{
    protected static ?string $model = Degree::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $modelLabel = 'Studiengang';

    protected static ?string $pluralModelLabel = 'Studiengänge';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Data;

    protected static ?string $recordTitleAttribute = 'display_name';

    public static function form(Schema $schema): Schema
    {
        return DegreeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DegreesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDegrees::route('/'),
        ];
    }

    /**
     * @return Builder<Degree>
     */
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
