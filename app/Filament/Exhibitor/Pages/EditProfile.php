<?php

namespace App\Filament\Exhibitor\Pages;

use App\Filament\Enums\NavigationGroup;
use BackedEnum;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Route;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class EditProfile extends \Filament\Auth\Pages\EditProfile
{
    protected static bool $isDiscovered = true;

    protected static ?string $navigationLabel = 'Benutzer-Profil';

    protected static string|BackedEnum|null $navigationIcon = Phosphor::UserCircleDuotone;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Profiles;

    public static function registerRoutes(Panel $panel): void
    {
        if (filled(static::getCluster())) {
            Route::name(static::prependClusterRouteBaseName($panel, ''))
                ->prefix(static::prependClusterSlug($panel, ''))
                ->group(fn () => static::routes($panel));

            return;
        }

        Route::name('pages.')->group(fn () => static::routes($panel));
    }

    public static function getRouteName(?Panel $panel = null): string
    {
        $panel ??= Filament::getCurrentOrDefaultPanel();

        $routeName = 'pages.'.static::getRelativeRouteName($panel);
        $routeName = static::prependClusterRouteBaseName($panel, $routeName);

        return $panel->generateRouteName($routeName);
    }
}
