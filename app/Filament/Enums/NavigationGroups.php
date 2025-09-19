<?php

namespace App\Filament\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum NavigationGroups implements HasLabel
{
    case Data;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Data => 'Stammdaten'
        };
    }
}
