<?php

namespace App\Livewire\App\User\Denda;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Denda')]
#[Layout('livewire.layouts.main-app')]
class Denda extends Component
{
    public $fines;

    public function mount()
    {
        $user = Auth::user();

        $this->fines = $user->fines()
            ->with(['loan.book'])
            ->get();
    }

    public function render()
    {
        return view('livewire.app.user.denda.denda', [
            'fines' => $this->fines,
        ]);
    }
}
