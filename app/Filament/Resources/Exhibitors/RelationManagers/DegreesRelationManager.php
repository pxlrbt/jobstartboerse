<?php

namespace App\Filament\Resources\Exhibitors\RelationManagers;

use App\Filament\Resources\Degrees\DegreeResource;
use App\Filament\Resources\Degrees\Schemas\DegreeForm;
use App\Filament\Resources\Degrees\Tables\DegreesTable;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
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
                CreateAction::make(),
                AttachAction::make()->multiple(),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
