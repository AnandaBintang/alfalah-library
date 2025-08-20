<?php

namespace App\Providers\Filament;

use App\Enum\RoleEnum;
use App\Filament\Resources\DonationResource\Widgets\DonationChart;
use App\Filament\Resources\LoanChartResource\Widgets\LoanChart;
use App\Filament\Resources\ReturnedResource\Widgets\ReturnedChart;
use App\Filament\Resources\VisitResource\Widgets\VisitChart;
use App\Filament\Widgets\TopVisitors;
use App\Filament\Widgets\UserStats;
use App\Http\Middleware\CheckActiveUser;
use App\Http\Middleware\EnsureUserHasAdminRole;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class AdminPanelProvider extends PanelProvider
{
  public function panel(Panel $panel): Panel
  {
    return $panel
      ->default()
      ->id('admin')
      ->path('admin')
      ->login()
      ->passwordReset()
      ->emailVerification()
      ->spa()
      ->favicon(asset('logo/logo-alfalah.png'))
      ->colors([
        'primary' => Color::Amber,
      ])
      ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
      ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
      ->favicon(asset('logo/logo-alfalah.png'))
      ->pages([
        Pages\Dashboard::class,
      ])
      ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
      ->plugins([
        FilamentApexChartsPlugin::make(),
      ])
      ->widgets([
        //        Widgets\AccountWidget::class,
        UserStats::class,
        LoanChart::make(),
        ReturnedChart::make(),
        DonationChart::make(),
        VisitChart::make(),
        TopVisitors::class,
      ])
      ->sidebarCollapsibleOnDesktop()
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
        CheckActiveUser::class,
        EnsureUserHasAdminRole::class,
      ]);
  }
}
