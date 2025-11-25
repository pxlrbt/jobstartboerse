<?php

namespace App\Filament\Panels\Admin\Resources\Exhibitors\RelationManagers;

use App\Filament\Panels\Admin\Resources\Professions\ProfessionResource;
use App\Filament\Panels\Admin\Resources\Professions\Schemas\ProfessionForm;
use App\Filament\Panels\Admin\Resources\Professions\Tables\ProfessionsTable;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProfessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'professions';

    protected static ?string $relatedResource = ProfessionResource::class;

    public function form(Schema $schema): Schema
    {
        return ProfessionForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return ProfessionsTable::configure($table)
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
