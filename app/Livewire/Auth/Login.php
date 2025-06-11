<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.auth-app')]
#[Title('Buku')]
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

    if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
      session()->regenerate();

      return redirect()->route('book.index');
    } else {
      LivewireAlert::title('Error!')
        ->text('Account atau password salah.')
        ->position('center')
        ->timer(5500)
        ->error()
        ->show();
    }
  }

  public function render()
  {
    return view('livewire.auth.login');
  }
}
