<?php

namespace App\Livewire\Components;

use App\Models\Kegiatan;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalKegiatan extends Component
{

  public $show;
  public $kegiatanId;
  public $kegiatan;

  #[On('open-modal')]
  public function open($id)
  {
    $this->show = true;
    $this->kegiatanId = $id;
    $this->kegiatan = Kegiatan::findOrFail($this->kegiatanId);

  }
    public function render()
    {
        return view('livewire.components.modal-kegiatan', [
          'kegiatans' => $this->kegiatan
        ]);
    }
}
