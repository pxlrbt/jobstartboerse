<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Login;
use Filafly\Icons\Phosphor\PhosphorIcons;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
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
            ->sidebarCollapsibleOnDesktop()

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
            ->plugin(PhosphorIcons::make()->style('duotone'))
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn () => <<<'HTML'
                    <style>
                        .fi-sc-tabs.fi-vertical {
                            align-items: start;
                        }

                        .fi-tabs:not(.fi-contained) {
                            margin-inline: 0;
                        }
                    </style>
                HTML);
    }
}
