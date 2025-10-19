<?php

namespace App\Filament\Exhibitor\Pages;

use App\Filament\Exhibitor\RelationManagers\ProfessionRelationManager;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageProfessions extends Page
{
    protected static ?string $title = 'Berufe';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;

    public function content(Schema $schema): Schema
    {
        $record = auth()->user()->exhibitor;
        $manager = ProfessionRelationManager::make();

        return $schema->components([
            Livewire::make(
                $manager->relationManager,
                [
                    'ownerRecord' => $record,
                    'pageClass' => self::class,
                ]
            ),
        ]);
    }
}
