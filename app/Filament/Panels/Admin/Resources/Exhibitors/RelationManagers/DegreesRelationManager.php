<?php

namespace App\Filament\Panels\Admin\Resources\Exhibitors\RelationManagers;

use App\Filament\Panels\Admin\Resources\Degrees\DegreeResource;
use App\Filament\Panels\Admin\Resources\Degrees\Schemas\DegreeForm;
use App\Filament\Panels\Admin\Resources\Degrees\Tables\DegreesTable;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Table;

class DegreesRelationManager extends RelationManager
{
    protected static string $relationship = 'degrees';

    protected static ?string $relatedResource = DegreeResource::class;

    public function form(Schema $schema): Schema
    {
        return DegreeForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return DegreesTable::configure($table)
            ->headerActions([
                AttachAction::make()
                    ->modalWidth(Width::Large)
                    ->multiple(),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalWidth(Width::Large),

                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
