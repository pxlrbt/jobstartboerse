<?php

namespace App\Filament\Panels\Admin\Resources\Surveys\Actions;

use App\Actions\GetFilamentComponentForSurveyQuestion;
use App\Models\Survey;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Actions\Action;

class PreviewSurveyAction
{
    public static function make(): Action
    {
        return Action::make('preview')
            ->label('Vorschau')
            ->color('gray')
            ->icon(Phosphor::ProjectorScreenChartDuotone)
            ->slideOver()
            ->schema(function ($livewire) {
                /** @var Survey $record */
                $record = $livewire->record;
                $getComponent = resolve(GetFilamentComponentForSurveyQuestion::class);

                return collect($record->questions->map($getComponent(...)))->toArray();
            });
    }
}
