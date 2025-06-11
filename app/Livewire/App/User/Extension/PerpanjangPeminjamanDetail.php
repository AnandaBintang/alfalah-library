<?php

namespace App\Livewire\App\User\Extension;

use App\Models\Loan;
use App\Models\LoanExtension;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Perpanjang Peminjaman')]
#[Layout('livewire.layouts.main-app')]
class PerpanjangPeminjamanDetail extends Component
{
  public $data;

  public function mount($id)
  {
    $this->data = LoanExtension::find($id);
  }

  public function render()
  {
    return view('livewire.app.user.extension.perpanjang-peminjaman-detail',
      [
        'data' => $this->data,
      ]);
  }
}
