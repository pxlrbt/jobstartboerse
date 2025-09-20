<?php

namespace App\Filament\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup implements HasLabel
{
    case Data;

    public function getLabel(): string
    {
        return match ($this) {
            self::Data => 'Stammdaten'
        };
    }
}
