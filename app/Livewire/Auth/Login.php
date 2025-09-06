<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.auth-app')]
#[Title('Login')]
class Login extends Component
{

  public $email = '';

  public $password = '';

  public $remember = false;

  public $error = '';

  protected $rules = [
    'email' => 'required|email',
    'password' => 'required|min:6',
  ];

  public function login()
  {
    $this->validate();

    $user = User::where('email', $this->email)->first();

    if (!$user) {
      LivewireAlert::title('Error!')
        ->text('Akun tidak ditemukan.')
        ->position('top-end')
        ->timer(5500)
        ->error()
        ->toast()
        ->show();
      return;
    }

    if ($user->is_active == 0) {
      LivewireAlert::title('Akun Tidak Aktif!')
        ->text('Akun Anda tidak aktif. Silakan hubungi administrator untuk mengaktifkan akun.')
        ->withConfirmButton('Ok')
        ->error()
        ->timer(8000)
        ->show();
      return;
    }

    if ($user->activated_at == null) {
      $user->activated_at = now();
      $user->save();
    }

    if ($user->expires_at == null) {
      $user->expires_at = now()->addYear(3);
      $user->save();
    }

    if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
      session()->regenerate();

      return redirect()->route('book.index');
    } else {
      LivewireAlert::title('Error!')
        ->text('Email atau password salah.')
        ->position('top-end')
        ->timer(5500)
        ->error()
        ->toast()
        ->show();
      return;
    }
  }

  public function render()
  {
    return view('livewire.auth.login');
  }
}
