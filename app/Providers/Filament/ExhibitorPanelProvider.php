<?php

namespace App\Providers\Filament;

use App\Enums\Role;
use App\Filament\Enums\NavigationGroup;
use App\Filament\Pages\Login;
use App\Filament\Panels\Exhibitor\RelationManagers\DegreeRelationManager;
use App\Filament\Panels\Exhibitor\RelationManagers\JobStartRelationManager;
use App\Filament\Panels\Exhibitor\RelationManagers\ProfessionRelationManager;
use App\Models\Exhibitor;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filafly\Icons\Phosphor\PhosphorIcons;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
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
            ->tenant(Exhibitor::class)
            ->login(Login::class)
            ->brandLogo('/logo.svg')
            ->brandName('Jobstartbörse')
            ->favicon('/favicon.svg')
            ->passwordReset()

            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])

            ->navigationItems([
                NavigationItem::make('exhibitor_panel')
                    ->label('Admin-Bereich')
                    ->group(NavigationGroup::Links)
                    ->icon(Phosphor::SignInDuotone)
                    ->url(function () {
                        Filament::setCurrentPanel('admin');
                        $url = Filament::getUrl();
                        Filament::setCurrentPanel('exhibitor');

                        return $url;
                    })
                    ->visible(fn () => auth()->user()->role === Role::Admin),
            ])

            ->discoverResources(in: app_path('Filament/Panels/Exhibitor/Resources'), for: 'App\Filament\Panels\Exhibitor\Resources')
            ->discoverPages(in: app_path('Filament/Panels/Exhibitor/Pages'), for: 'App\Filament\Panels\Exhibitor\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Panels/Exhibitor/Widgets'), for: 'App\Filament\Panels\Exhibitor\Widgets')
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
