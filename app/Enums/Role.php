<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Role: int implements HasLabel
{
    case Admin = 5;

    case Exhibitor = 6;

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'Verwaltung',
            self::Exhibitor => 'Aussteller',
        };
    }
}
