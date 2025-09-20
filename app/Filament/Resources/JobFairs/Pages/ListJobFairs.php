<?php

namespace App\Filament\Resources\JobFairs\Pages;

use App\Filament\Resources\JobFairs\JobFairResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListJobFairs extends ListRecords
{
    protected static string $resource = JobFairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'upcoming' => Tab::make('Anstehend')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas(
                    'dates',
                    fn (Builder $query) => $query->whereAfterToday('date')
                )),

            'archive' => Tab::make('Archiv')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave(
                    'dates',
                    fn (Builder $query) => $query->whereAfterToday('date')
                )),
        ];
    }
}
