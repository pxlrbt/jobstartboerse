<?php

namespace App\Filament\Panels\Admin\Resources\Users\Pages;

use App\Enums\Role;
use App\Filament\Panels\Admin\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make(Role::Exhibitor->getLabel())
                ->modifyQueryUsing(fn ($query) => $query->where('role', Role::Exhibitor))
                ->badge(function (ListUsers $livewire) {

                    $tab = array_find(
                        $livewire->getCachedTabs(),
                        fn (Tab $tab) => $tab->getLabel() === Role::Exhibitor->getLabel()
                    );

                    $table = invade($livewire->getTable());
                    $scopes = $table->queryScopes;
                    $table->queryScopes = [];

                    $query = $table->getQuery();

                    $count = $tab->modifyQuery($query)->count();

                    $table->queryScopes = $scopes;

                    return $count;
                }),

            Tab::make(Role::Admin->getLabel())
                ->modifyQueryUsing(fn ($query) => $query->where('role', Role::Admin))
                ->badge(function (ListUsers $livewire) {
                    $tab = array_find(
                        $livewire->getCachedTabs(),
                        fn (Tab $tab) => $tab->getLabel() === Role::Admin->getLabel()
                    );

                    $table = invade($livewire->getTable());
                    $scopes = $table->queryScopes;
                    $table->queryScopes = [];

                    $query = $table->getQuery();

                    $count = $tab->modifyQuery($query)->count();

                    $table->queryScopes = $scopes;

                    return $count;
                }),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
