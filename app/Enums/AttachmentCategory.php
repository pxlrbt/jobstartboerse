<?php

namespace App\Enums;

enum AttachmentCategory: int
{
    case Visitors = 1;
    case Exhibitors = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::Visitors => 'Besucher',
            self::Exhibitors => 'Aussteller',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (AttachmentCategory $item) => [$item->value => $item->getLabel()])
            ->sort()
            ->toArray();
    }
}
