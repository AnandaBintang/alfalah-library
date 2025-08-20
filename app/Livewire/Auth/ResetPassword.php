<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Layout('livewire.layouts.auth-app')]
#[Title('Reset Password')]
class ResetPassword extends Component
{
  public string $email = '';
  public string $token = '';
  public string $password = '';
  public string $password_confirmation = '';

  public function mount($token)
  {
    $this->email = request()->query('email');
    $this->token = $token;
  }

  public function resetPassword()
  {
    $this->validate([
      'email' => 'required|email',
      'password' => 'required|confirmed|min:8',
    ]);

    $status = Password::reset(
      [
        'email' => $this->email,
        'password' => $this->password,
        'password_confirmation' => $this->password_confirmation,
        'token' => $this->token,
      ],
      function ($user) {
        $user->forceFill([
          'password' => Hash::make($this->password),
        ])->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));
      }
    );

    if ($status === Password::PASSWORD_RESET) {
      return redirect()->route('login')->with('status', 'Password berhasil dirubah');
    }
  }

  public function render()
  {
    return view('livewire.auth.reset-password', [
      'email' => $this->email,
    ]);
  }
}
