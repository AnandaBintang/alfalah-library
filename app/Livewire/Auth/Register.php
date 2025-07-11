<?php

namespace App\Livewire\Auth;

use App\Enum\RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.auth-app')]
#[Title('Register')]
class Register extends Component
{
    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $error = '';

    protected $rules = [
        'name' => 'required|min:3|max:50',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ];

    public function register()
    {
        $this->validate();

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $user->assignRole(RoleEnum::SISWA->value);

            Auth::login($user);

            return redirect()->route('book.index');
        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan saat registrasi.';
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
