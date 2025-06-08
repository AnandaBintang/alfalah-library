<?php

namespace App\Livewire\App\User\Extension;

use App\Models\LoanExtension;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Riwayat Perpanjang Peminjaman')]
#[Layout('livewire.layouts.main-app')]
class PerpanjangPeminjaman extends Component
{
    public $data;

    public function render()
    {
        $this->data = LoanExtension::with(['loan.book'])
            ->whereHas('loan', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('livewire.app.user.extension.perpanjang-peminjaman', [
            'data' => $this->data,
        ]);
    }
}
