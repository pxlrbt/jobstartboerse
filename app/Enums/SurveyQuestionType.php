<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SurveyQuestionType: string implements HasLabel
{
    // case Input = 'input'; // 1 Textantwort
    // Styles: Text, Numeric, Email

    case Text = 'textarea'; // 1 Textantwort
    // Text/Editor

    case SingleChoice = 'single-choice';   // 3
    // Styles: Select/Radio/Radio Buttons/Slider/Rating

    case MultipleChoice = 'multiple-choice';   // 4
    // Styles: Checkboxes/Checkbox Buttons

    case Toggle = 'toggle';   // 5 Yes/No
    // Styles: Checkbox/Toggle

    case Rating = 'rating';  // 2 Rating
    //

    public function hasOptions(): bool
    {
        return match ($this) {
            self::SingleChoice, self::MultipleChoice => true,
            default => false,
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => 'Textfeld',
            self::SingleChoice => 'Einfach-Auswahl',
            self::MultipleChoice => 'Mehrfach-Auswahl',
            self::Toggle => 'Ja/Nein',
            self::Rating => 'Bewertung'
        };
    }
}
