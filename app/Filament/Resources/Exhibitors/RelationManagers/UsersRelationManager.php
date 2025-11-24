<?php

namespace App\Filament\Resources\Exhibitors\RelationManagers;

use App\Enums\Role;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Benutzer';

    protected static ?string $modelLabel = 'Benutzer';

    protected static ?string $pluralModelLabel = 'Benutzer';

    public function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return UsersTable::configure($table)
            ->headerActions([
                CreateAction::make()
                    ->fillForm(fn (UsersRelationManager $livewire) => [
                        'exhibitor_id' => $livewire->getOwnerRecord()->getKey(),
                        'role' => Role::Exhibitor,
                    ]),
            ]);
    }
}
