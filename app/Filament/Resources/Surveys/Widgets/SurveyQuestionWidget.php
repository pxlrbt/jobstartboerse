<?php

namespace App\Filament\Resources\Surveys\Widgets;

use App\Models\SurveyQuestion;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class SurveyQuestionWidget extends ChartWidget
{
    public SurveyQuestion $question;

    protected bool $isCollapsible = true;

    protected ?string $maxHeight = '15rem';

    public function getHeading(): string|Htmlable|null
    {
        return $this->question->display_name;
    }

    protected function getData(): array
    {
        $this->question->loadMissing('answers');

        $answers = $this->question->answers->groupBy('content');
        $answers = $answers->map(fn ($answer) => $answer->count());

        $values = $answers->values();
        $labels = $answers->keys();

        return [
            'datasets' => [
                [
                    'label' => 'Antworten',
                    'data' => $values->toArray(),
                    'backgroundColor' => [
                        '#6B9AC4',
                        '#98B8A8',
                        '#E8B298',
                        '#B8A6D0',
                        '#F4C6A3',
                        '#8EB1C7',
                        '#C4A8A4',
                        '#A8C5A0',
                    ],
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
