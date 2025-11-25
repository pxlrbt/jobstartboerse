<?php

namespace App\Filament\Panels\Admin\Resources\Surveys\Pages;

use App\Filament\Panels\Admin\Resources\Surveys\SurveyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSurveys extends ListRecords
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
