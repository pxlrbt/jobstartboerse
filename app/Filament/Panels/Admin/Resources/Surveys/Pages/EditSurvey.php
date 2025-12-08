<?php

namespace App\Filament\Panels\Admin\Resources\Surveys\Pages;

use App\Filament\Panels\Admin\Resources\Surveys\Actions\PreviewSurveyAction;
use App\Filament\Panels\Admin\Resources\Surveys\SurveyResource;
use App\Models\Survey;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSurvey extends EditRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_results')
                ->label('Ergebnisse')
                ->color('gray')
                ->icon(Phosphor::ChartScatterDuotone)
                ->visible(fn (Survey $record): bool => $record->submissions()->exists())
                ->url(fn (Survey $record) => SurveyResource::getUrl('results', ['record' => $record->id])),

            PreviewSurveyAction::make(),

            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
