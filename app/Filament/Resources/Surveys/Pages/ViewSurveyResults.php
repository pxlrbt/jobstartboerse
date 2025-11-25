<?php

namespace App\Filament\Resources\Surveys\Pages;

use App\Enums\SurveyQuestionType;
use App\Filament\Resources\Surveys\Actions\ExportSurveyAction;
use App\Filament\Resources\Surveys\SurveyResource;
use App\Filament\Resources\Surveys\Widgets\SurveyQuestionWidget;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;

class ViewSurveyResults extends ViewRecord
{
    protected static string $resource = SurveyResource::class;

    protected Width|string|null $maxContentWidth = Width::ScreenLarge;

    public function getTitle(): string|Htmlable
    {
        /** @var Survey $survey */
        $survey = $this->record;

        return $survey->display_name;
    }

    public function content(Schema $schema): Schema
    {
        /**
         * @var Survey $survey
         */
        $survey = $this->record;
        $survey->load('questions.answers');
        $components = $survey->questions
            ->map($this->getWidgetForQuestion(...))
            ->flatten()
            ->toArray();

        return $schema->columns(2)->components([
            Section::make()
                ->collapsible(false)
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    TextEntry::make('count')
                        ->label('Teilnehmer')
                        ->state($survey->submissions()->count()),

                    TextEntry::make('created_at')
                        ->label('Letzte Teilnahme')
                        ->dateTime('d.m.Y H:i')
                        ->state($survey->submissions()->latest()->first()?->created_at),
                ]),
            ...$components,
        ]);
    }

    protected function getWidgetForQuestion(SurveyQuestion $question): Section|Livewire
    {
        if ($question->type === SurveyQuestionType::Text) {
            return Section::make($question->display_name)
                ->columnSpanFull()
                ->components([
                    RepeatableEntry::make('answers')
                        ->hiddenLabel()
                        ->state($question->answers)
                        ->components([
                            TextEntry::make('content')->hiddenLabel(),
                        ]),
                ]);
        }

        return
            Livewire::make(
                SurveyQuestionWidget::class, ['question' => $question, 'type' => 'bar']
            )->key($question->id.'-bar')->columnSpanFull();
    }

    protected function getHeaderActions(): array
    {
        return [
            ExportSurveyAction::make(),
        ];
    }
}
