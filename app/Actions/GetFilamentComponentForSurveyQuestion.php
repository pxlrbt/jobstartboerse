<?php

namespace App\Actions;

use App\Enums\SurveyQuestionType;
use App\Models\SurveyQuestion;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Component;

class GetFilamentComponentForSurveyQuestion
{
    public function __invoke(SurveyQuestion $question): Component
    {
        if ($question->type === SurveyQuestionType::SingleChoice) {
            return ToggleButtons::make((string) $question->id)
                ->label($question->display_name)
                ->options($question->options)
                ->inline()
                ->required();
        }

        if ($question->type === SurveyQuestionType::MultipleChoice) {
            return ToggleButtons::make((string) $question->id)
                ->label($question->display_name)
                ->helperText('Mehrfachauswahl möglich')
                ->inline()
                ->multiple()
                ->options($question->options)
                ->required();
        }

        if ($question->type === SurveyQuestionType::Toggle) {
            return ToggleButtons::make((string) $question->id)
                ->label($question->display_name)
                ->boolean()
                ->inline()
                ->required();
        }

        if ($question->type === SurveyQuestionType::Rating) {
            return ToggleButtons::make((string) $question->id)
                ->label($question->display_name)
                ->inline()
                ->options([
                    1 => 'Sehr Gut',
                    2 => 'Gut',
                    3 => 'Zufriedenstellend',
                    4 => 'Nicht zufriedenstellend',
                ])
                ->required();
        }

        return Textarea::make((string) $question->id)
            ->label($question->display_name)
            ->rows(3)
            ->required();
    }
}
