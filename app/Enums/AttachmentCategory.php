<?php

namespace App\Enums;

use Illuminate\Contracts\Support\Htmlable;

enum AttachmentCategory: int
{
    case Visitors = 1;
    case Exhibitors = 2;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Visitors => 'Besucher',
            self::Exhibitors => 'Aussteller',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (AttachmentCategory $item) => [$item->value => $item->label])
            ->sort()
            ->toArray();
    }
}
