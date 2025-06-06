<?php

namespace App\Livewire\App\User\Donasi;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('New Donasi')]
#[Layout('livewire.layouts.main-app')]
class CreateDonasi extends Component
{
    public function render()
    {
        return view('livewire.app.user.donasi.create-donasi');
    }
}
