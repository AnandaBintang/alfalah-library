<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Perpustakaan Al-Falah')]
#[Layout('livewire.layouts.main-app')]
class LandingPage extends Component
{
    public function render()
    {
        return view('livewire.landing-page');
    }
}
