<?php

namespace App\Livewire\Auth;

use App\Trait\NotificationsAndDialog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.auth-app')]
#[Title('Buku')]
class Login extends Component
{
    use NotificationsAndDialog;

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

            return redirect()->intended('/');
        } else {
            $this->errorNotification('Error', 'Account atau password salah.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
