<?php

namespace App\Filament\Panels\Admin\Resources\Surveys\Widgets;

use App\Models\SurveyQuestion;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class SurveyQuestionWidget extends ChartWidget
{
    public SurveyQuestion $question;

    public string $type = 'pie';

    protected bool $isCollapsible = true;

    // protected ?string $maxHeight = '15rem';

    public function getHeading(): string|Htmlable|null
    {
        return $this->question->display_name;
    }

    protected function getData(): array
    {
        $this->question->loadMissing('answers');

        $answers = $this->question->answers->groupBy('textContent');
        $answers = $answers->map(fn ($answer) => $answer->count());

        $values = $answers->values();
        $labels = $answers->keys();

        return [
            'datasets' => [
                [
                    'label' => 'Antworten',
                    'data' => $values->toArray(),

                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)',
                    ],
                    'borderColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)',
                    ],
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getOptions(): array|RawJs|null
    {
        return [
            'indexAxis' => 'y',
            // 'barThickness' => 24,
            // 'borderWidth' => 1,
            // 'borderRadius' => 3,
            'skipNull' => false,
            // 'aspectRatio' => 5,
            'maintainAspectRatio' => true,
        ];
    }

    protected function getType(): string
    {
        return $this->type;
    }
}
