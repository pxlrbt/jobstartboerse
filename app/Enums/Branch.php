<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Branch: int implements HasLabel
{
    case General = 1;
    case Financial = 2;
    case Education = 3;
    case Retail = 4;
    case Healthcare = 5;
    case Wholesale = 6;
    case Crafts = 7;
    case Hospitality = 8;
    case Manufacturing = 9;
    case Technology = 10;
    case Associations = 11;
    case Logistics = 12;
    case PublicSector = 13;

    public function getLabel(): string
    {
        return match ($this) {
            self::General => 'Allgemeine Dienstleistungen',
            self::Financial => 'Banken, Versicherungen',
            self::Education => 'Bildungseinrichtungen',
            self::Retail => 'Einzelhandel',
            self::Healthcare => 'Gesundheit, Pflege, Soziales',
            self::Wholesale => 'Groß-, Außenhandel',
            self::Crafts => 'Handwerk',
            self::Hospitality => 'Hotels, Gaststätten',
            self::Manufacturing => 'Industrie',
            self::Technology => 'IT, Medienwirtschaft',
            self::Associations => 'Kammern, Verbände',
            self::Logistics => 'Logistik',
            self::PublicSector => 'Öffentliche Verwaltungen, Kirchliche Einrichtungen',
        };
    }
}
