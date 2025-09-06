<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (Auth::check()) {
      $user = Auth::user();

      if (!$user->is_active) {
        Auth::logout();

        Notification::make()
          ->title('Akun Tidak Aktif')
          ->body('Akun Anda telah dinonaktifkan. Silakan hubungi administrator.')
          ->danger()
          ->persistent()
          ->send();

        return redirect()->route('filament.admin.auth.login');
      }
    }

    return $next($request);
  }
}
