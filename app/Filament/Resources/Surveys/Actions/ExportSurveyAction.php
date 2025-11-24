<?php

namespace App\Filament\Resources\Surveys\Actions;

use App\Filament\Resources\Surveys\Pages\ViewSurveyResults;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Actions\Action;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Writer\XLSX\Writer;

class ExportSurveyAction
{
    public static function make(): Action
    {
        return Action::make('export')
            ->label('Exportieren')
            ->icon(Phosphor::ExportDuotone)
            ->action(function (ViewSurveyResults $livewire) {
                /**
                 * @var Survey $survey
                 */
                $survey = $livewire->getRecord();
                $survey->load('questions.answers');

                $filepath = tempnam(sys_get_temp_dir(), 'filament-export');

                $options = new Options;
                $writer = new Writer($options);
                $writer->openToFile($filepath);

                $writer->addRow(
                    new Row(
                        $survey->questions->map(function (SurveyQuestion $question, $index) use ($options) {
                            $options->setColumnWidth(strlen($question->display_name), $index + 1);

                            return Cell::fromValue($question->display_name);
                        })->toArray(),
                        (new Style)->setFontBold(),
                    )
                );

                $columns = [];

                foreach ($survey->questions as $question) {
                    $columns[] = $question->answers->map(
                        function (SurveyAnswer $answer) {
                            $content = $answer->content;

                            if (is_array($content)) {
                                $content = implode(', ', $content);
                            }

                            return Cell::fromValue($content);
                        }
                    )->toArray();
                }

                $rows = static::transpose($columns);

                foreach ($rows as $row) {
                    $writer->addRow(new Row($row));
                }

                $writer->close();

                return response()->download(
                    $filepath,
                    date('Y-m-d').'-'.$survey->display_name.'.xlsx'
                );
            });
    }

    protected static function transpose(array $array): array
    {
        $result = [];

        foreach ($array as $key => $subarray) {
            foreach ($subarray as $subkey => $subvalue) {
                $result[$subkey][$key] = $subvalue;
            }
        }

        return $result;
    }
}
