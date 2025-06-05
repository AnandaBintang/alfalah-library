<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Perpustakaan Al-Falah')]
class LandingPage extends Component
{
    public function render()
    {
        return view('livewire.landing-page');
    }
}
