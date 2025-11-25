<?php

namespace App\Filament\Panels\Exhibitor\Resources\Surveys\Pages;

use App\Enums\SurveyQuestionType;
use App\Filament\Panels\Exhibitor\Resources\Surveys\SurveyResource;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveySubmission;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;

class ParticipateSurvey extends EditRecord
{
    protected static string $resource = SurveyResource::class;

    protected Width|string|null $maxContentWidth = Width::ScreenSmall;

    public function mount(string|int $record): void
    {
        $submissionExists = SurveySubmission::query()
            ->where('exhibitor_id', auth()->user()->exhibitor->id)
            ->where('survey_id', $record)
            ->exists();

        if ($submissionExists) {
            $this->redirect(SurveyResource::getUrl());
        }

        parent::mount($record);
    }

    protected function getSavedNotificationMessage(): ?string
    {
        return 'Danke für Ihr Feedback!';
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()->label('Absenden');
    }

    public function form(Schema $schema): Schema
    {
        /** @var Survey $record */
        $record = $this->record;

        return $schema
            ->columns(1)
            ->components(
                collect($record->questions->map($this->getComponentForQuestion(...)))->toArray(),
            );
    }

    protected function getComponentForQuestion(SurveyQuestion $question): Component
    {
        if ($question->type === SurveyQuestionType::SingleChoice) {
            return ToggleButtons::make((string) $question->id)
                ->label($question->display_name)
                ->options($question->options)
                ->required();
        }

        if ($question->type === SurveyQuestionType::MultipleChoice) {
            return ToggleButtons::make((string) $question->id)
                ->label($question->display_name)
                ->multiple()
                ->options($question->options)
                ->required();
        }

        if ($question->type === SurveyQuestionType::Toggle) {
            return ToggleButtons::make((string) $question->id)
                ->label($question->display_name)
                ->boolean()
                // ->options([
                //     1 => 'Ja',
                //     0 => 'Nein',
                // ])
                ->required();
        }

        if ($question->type === SurveyQuestionType::Rating) {
            return ToggleButtons::make((string) $question->id)
                ->label($question->display_name)
                ->options([
                    1 => 'Ja',
                    0 => 'Nein',
                ])
                ->required();
        }

        return TextInput::make((string) $question->id)
            ->label($question->display_name)
            ->required();
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $answers = $this->form->getState();

        /** @var Survey $record */
        $record = $this->record;

        $submission = SurveySubmission::create([
            'survey_id' => $record->id,
            'exhibitor_id' => auth()->user()->exhibitor_id,
        ]);

        foreach ($answers as $questionId => $answer) {
            SurveyAnswer::create([
                'survey_submission_id' => $submission->id,
                'survey_question_id' => $questionId,
                'content' => $answer,
            ]);
        }

        $this->getSavedNotification()->send();

        $this->redirect(SurveyResource::getUrl());
    }

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
