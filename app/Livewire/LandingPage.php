<?php

namespace App\Livewire;

use App\Models\Kegiatan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Perpustakaan Al-Falah')]
#[Layout('livewire.layouts.app')]
class LandingPage extends Component
{
  public $totalKegiatan;

  public function mount(Kegiatan $kegiatan)
  {
    $this->totalKegiatan = $kegiatan->where('is_active', 1)->count();
  }
    public function render()
    {
        return view('livewire.landing-page');
    }
}
