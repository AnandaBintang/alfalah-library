<?php

namespace App\Livewire\App\User\Peminjaman;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Peminjaman')]
#[Layout('livewire.layouts.main-app')]
class Peminjaman extends Component
{
    public $data;

    public function loadData()
    {
        $user = Auth::user();
        $this->data = $user->loans()->get();
    }

    public function render()
    {
        $this->loadData();

        return view('livewire.app.user.peminjaman.peminjaman', [
            'datas' => $this->data,
        ]);
    }
}
