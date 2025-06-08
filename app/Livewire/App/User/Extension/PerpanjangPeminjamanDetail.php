<?php

namespace App\Livewire\App\User\Extension;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Perpanjang Peminjaman')]
#[Layout('livewire.layouts.main-app')]
class PerpanjangPeminjamanDetail extends Component
{
    public function render()
    {
        return view('livewire.app.user.extension.perpanjang-peminjaman-detail');
    }
}
