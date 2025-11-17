<?php

namespace App\Livewire\Components\Landingpage;

use Livewire\Component;
use App\Models\Kegiatan as ModelKegiatan;

class Kegiatan extends Component
{



  public function render(ModelKegiatan $kegiatan)
  {
    $datas = $kegiatan->where('is_active', 1)->get();
    return view('livewire.components.landingpage.kegiatan', [
      'datas' => $datas
    ]);
  }
}
