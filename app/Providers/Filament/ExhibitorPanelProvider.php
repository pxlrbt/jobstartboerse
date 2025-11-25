<?php

namespace App\Providers\Filament;

use App\Filament\Exhibitor\RelationManagers\DegreeRelationManager;
use App\Filament\Exhibitor\RelationManagers\JobStartRelationManager;
use App\Filament\Exhibitor\RelationManagers\ProfessionRelationManager;
use App\Filament\Pages\Login;
use Filafly\Icons\Phosphor\PhosphorIcons;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Livewire\Livewire;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;

class ExhibitorPanelProvider extends PanelProvider
{
    public function register(): void
    {
        parent::register();

        Livewire::component(ProfessionRelationManager::class, ProfessionRelationManager::class);
        Livewire::component(DegreeRelationManager::class, DegreeRelationManager::class);
        Livewire::component(JobStartRelationManager::class, JobStartRelationManager::class);
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('exhibitor')
            ->path('aussteller')
            ->login(Login::class)
            ->brandLogo('/logo.svg')
            ->brandName('Jobstartbörse')
            ->favicon('/favicon.svg')
            ->passwordReset()

            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarCollapsibleOnDesktop()

            ->discoverResources(in: app_path('Filament/Exhibitor/Resources'), for: 'App\Filament\Exhibitor\Resources')
            ->discoverPages(in: app_path('Filament/Exhibitor/Pages'), for: 'App\Filament\Exhibitor\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Exhibitor/Widgets'), for: 'App\Filament\Exhibitor\Widgets')
            ->widgets([
                AccountWidget::class,
            ])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])

            ->plugin(EnvironmentIndicatorPlugin::make())
            ->plugin(PhosphorIcons::make()->style('duotone'));
    }
}
