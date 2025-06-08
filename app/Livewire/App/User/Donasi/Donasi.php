<?php

namespace App\Livewire\App\User\Donasi;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Donasi')]
#[Layout('livewire.layouts.main-app')]
class Donasi extends Component
{
    public $data;

    public function loadData()
    {
        $user = Auth::user();
        $this->data = $user->donations()->get();
    }

    public function render()
    {
        $this->loadData();

        return view('livewire.app.user.donasi.donasi', [
            'datas' => $this->data,
        ]);
    }
}
