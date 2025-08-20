<?php

namespace App\Providers;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    // Notif
    //    FilamentAsset::register([
    //      Js::make('sweetalert2', Vite::asset('resources/js/sweetalert2.js'))
    //    ]);

    // CUstom email
    VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
      return (new MailMessage)
        ->subject('Verifikasi Alamat Email Anda')
        ->greeting('Halo!')
        ->line('Terima kasih telah mendaftar di Perpustakaan Al-Falah.')
        ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.')
        ->action('Verifikasi Email', $url)
        ->line('Jika Anda tidak membuat akun, abaikan email ini.');
    });
  }
}
