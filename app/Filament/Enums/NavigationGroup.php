<?php

namespace App\Filament\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup implements HasLabel
{
    case Data;
    case Functions;

    case Profiles;

    public function getLabel(): string
    {
        return match ($this) {
            self::Data => 'Stammdaten',
            self::Functions => 'Funktionen',
            self::Profiles => 'Profile',
        };
    }
}
