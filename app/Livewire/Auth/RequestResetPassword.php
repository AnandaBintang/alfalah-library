<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Layout('livewire.layouts.auth-app')]
#[Title('Request Reset Password')]
class RequestResetPassword extends Component
{
  public string $email = '';

  public function submit()
  {
    $this->validate([
      'email' => 'required|email',
    ]);

    $status = Password::sendResetLink(['email' => $this->email]);

    if ($status === Password::RESET_LINK_SENT) {
      LivewireAlert::title('Success')
        ->text('Link reset dikirim ke email kamu!')
        ->withConfirmButton('Oke')
        ->timer(3500)
        ->success()
        ->show();
      $this->reset('email');
    } else {
      LivewireAlert::title('Error')
        ->text('Gagal mengirim link reset. Pastikan email valid.')
        ->withConfirmButton('Oke')
        ->timer(3500)
        ->error()
        ->show();
      $this->addError('email', __($status));
    }
  }

  public function render()
    {
        return view('livewire.auth.request-reset-password');
    }
}
