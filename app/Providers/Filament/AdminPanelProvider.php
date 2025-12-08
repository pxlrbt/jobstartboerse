<?php

namespace App\Providers\Filament;

use App\Filament\Enums\NavigationGroup;
use App\Filament\Pages\Login;
use Filafly\Icons\Phosphor\Enums\Phosphor;
use Filafly\Icons\Phosphor\PhosphorIcons;
use Filament\Actions\Action;
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
use Filament\Tables\Table;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        Table::configureUsing(fn (Table $table) => $table
            ->paginationPageOptions([25, 50, 100])
            ->defaultPaginationPageOption(50)
            ->modifyUngroupedRecordActionsUsing(fn (Action $action) => $action
                ->iconButton()
                ->tooltip(fn (Action $action) => $action->getLabel())
            )
        );

        return $panel
            ->default()
            ->id('admin')
            ->path('/')
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
                    ->label('Aussteller-Bereich')
                    ->group(NavigationGroup::Links)
                    ->icon(Phosphor::SignInDuotone)
                    ->url(function () {
                        Filament::setCurrentPanel('exhibitor');
                        $url = Filament::getUrl(tenant: auth()->user()->exhibitors->first());
                        Filament::setCurrentPanel('admin');

                        return $url;
                    })
                    ->visible(fn () => auth()->user()->exhibitors->isNotEmpty()),
            ])

            ->discoverResources(in: app_path('Filament/Panels/Admin/Resources'), for: 'App\Filament\Panels\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Panels/Admin/Pages'), for: 'App\Filament\Panels\Admin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Panels/Admin/Widgets'), for: 'App\Filament\Panels\Admin\Widgets')
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
