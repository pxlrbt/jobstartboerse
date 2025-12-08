<?php

namespace App\Filament\Panels\Exhibitor\Resources\Surveys\Pages;

use App\Actions\GetFilamentComponentForSurveyQuestion;
use App\Filament\Panels\Exhibitor\Resources\Surveys\SurveyResource;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveySubmission;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;

class ParticipateSurvey extends EditRecord
{
    protected static string $resource = SurveyResource::class;

    protected Width|string|null $maxContentWidth = Width::ScreenSmall;

    public function mount(string|int $record): void
    {
        $submissionExists = SurveySubmission::query()
            ->where('exhibitor_id', filament()->getTenant()->id)
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
        $getComponent = resolve(GetFilamentComponentForSurveyQuestion::class);

        return $schema
            ->columns(1)
            ->components(
                collect($record->questions->map($getComponent(...)))->toArray(),
            );
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $answers = $this->form->getState();

        /** @var Survey $record */
        $record = $this->record;

        $submission = SurveySubmission::create([
            'survey_id' => $record->id,
            'exhibitor_id' => filament()->getTenant()->id,
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
