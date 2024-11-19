<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->plugins([
                FilamentApexChartsPlugin::make()
            ])
            ->brandLogo(asset('/imagen/logo-admin.png'))
            ->favicon(asset('imagen/favicon-admin.ico'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
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
            ->databaseNotifications()
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_BEFORE, function () {
                return \Blade::render('
        <div class="text-sm">
            <a href="{{ env("APP_URL") }}" class="inline-flex items-center text-gray-800 dark:text-gray-200" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M3.055 11a9.01 9.01 0 0 1 6.277-7.598A16.9 16.9 0 0 0 7.029 11zm7.937-9.954C5.39 1.554 1 6.265 1 12s4.39 10.446 9.992 10.955l.008.01l.425.02A13 13 0 0 0 12 23a11 11 0 0 0 .575-.015l.425-.02l.008-.01C18.61 22.444 23 17.735 23 12S18.61 1.554 13.008 1.046L13 1.036l-.426-.021a11 11 0 0 0-1.148 0l-.426.02zM12.002 3a14.9 14.9 0 0 1 2.965 8H9.033a14.9 14.9 0 0 1 2.966-8H12M7.028 13c.16 2.76.98 5.345 2.303 7.598A9.01 9.01 0 0 1 3.055 13zm4.97 8a14.9 14.9 0 0 1-2.966-8h5.934A14.9 14.9 0 0 1 12 21zm2.67-.402A16.9 16.9 0 0 0 16.97 13h3.974a9.01 9.01 0 0 1-6.276 7.598M16.97 11c-.16-2.76-.98-5.345-2.303-7.598A9.01 9.01 0 0 1 20.945 11z"/></svg>
                <span class=" text-sm font-semibold" style="margin-left: 0.2rem;">Sitio Web</span>
            </a>
        </div>
    ');
            })
            ->pages([

            ])
            ->resources([
                config('filament-logger.activity_resource'),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
