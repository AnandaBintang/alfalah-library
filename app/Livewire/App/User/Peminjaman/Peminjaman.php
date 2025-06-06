<?php

namespace App\Livewire\App\User\Peminjaman;

use App\Enum\StatusLoanBookEnum;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Peminjaman')]
#[Layout('livewire.layouts.main-app')]
class Peminjaman extends Component
{
    public $data;

    public function mount($id)
    {
        $user = Auth::user();
        $this->data = $user->loans()->where('status', StatusLoanBookEnum::BORROWED->value)->get();
    }

    public function render()
    {
        return view('livewire.app.user.peminjaman.peminjaman', [
            'datas' => $this->data,
        ]);
    }
}
