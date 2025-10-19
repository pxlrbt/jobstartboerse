<?php

namespace App\Filament\Exhibitor\Pages;

use App\DataObjects\JobFairManager;
use App\Filament\Enums\NavigationGroup;
use App\Filament\Exhibitor\RelationManagers\DegreeRelationManager;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use UnitEnum;

class ManageDegrees extends Page
{
    protected static ?string $title = 'Studiengänge';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Data;

    public function content(Schema $schema): Schema
    {
        $record = auth()->user()->exhibitor;
        $manager = DegreeRelationManager::make();

        $managerRegional = JobFairManager::regional();
        $managerFreiburg = JobFairManager::freiburg();

        return $schema->components([
            new HtmlString(<<<HTML
                <div class="prose text-base max-w-3xl">
                    <p>Wählen Sie nachfolgend die Dualen Studienangebote, die in Ihrem Unternehmen angeboten werden.</p>
                    <p>Finden Sie Ihr Duales Studium nicht in der Übersicht, dann kontaktieren Sie bitte {$managerRegional->mailto()} oder das {$managerFreiburg->mailto()}.</p>
                </div>
            HTML),

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
