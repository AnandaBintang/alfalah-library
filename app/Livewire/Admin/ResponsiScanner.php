<?php

namespace App\Livewire\Admin;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Support\Carbon;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Responsi Scanner')]
#[Layout('livewire.layouts.scanner-app')]
class ResponsiScanner extends Component
{

  #[On('qrCodeScanned')]
  public function processScan($qrCode)
  {
    if (empty($qrCode)) {
      LivewireAlert::title('Error')
        ->text('Hasil scan kosong atau tidak valid.')
        ->timer(7000)
        ->withConfirmButton()
        ->error()
        ->show();
      return;
    }

    try {
      $user = User::whereHas('profile', function ($query) use ($qrCode) {
        $query->where('nis', $qrCode);
      })->first();

      if (!$user) {
        LivewireAlert::title('Error')
          ->text('User tidak ditemukan.')
          ->timer(5000)
          ->withConfirmButton()
          ->error()
          ->show();
        return;
      }

      // Create absensi
      Absensi::create([
        'user_id' => $user->id,
        'absensi_tanggal' => Carbon::now()
      ]);

      LivewireAlert::title('Success')
        ->text('Selamat datang ' . $user->name . ' di perpustakaan ' . config('app.name'))
        ->success()
        ->timer(10000)
        ->withConfirmButton()
        ->show();
      return;
    } catch (\Throwable $e) {
      \Log::error('Gagal scan ' . $e->getMessage());
    }

  }

  public function render()
  {
    return view('livewire.admin.responsi-scanner');
  }
}
