<?php

namespace App\Filament\Panels\Admin\Resources\Surveys\Pages;

use App\Filament\Panels\Admin\Resources\Surveys\SurveyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSurvey extends CreateRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
