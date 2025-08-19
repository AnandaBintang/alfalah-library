<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.auth-app')]
#[Title('Notifikasi Verifikasi')]
class VerifyNotice extends Component
{

  public function resendVerification()
  {
    if (Auth::user()->hasVerifiedEmail()) {
      $this->redirect(route('index'));
      return;
    }

    Auth::user()->sendEmailVerificationNotification();

    LivewireAlert::title('Sukses!')
      ->text('Link verifikasi baru sudah dikirim ke email kamu!')
      ->withConfirmButton('Oke')
      ->timer(3500)
      ->success()
      ->show();
  }

  public function render()
  {
    return view('livewire.auth.verify-notice');
  }
}
