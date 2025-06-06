<?php

namespace App\Livewire\App\User\Pengembalian;

use App\Enum\StatusLoanBookEnum;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Pengembalian')]
#[Layout('livewire.layouts.main-app')]
class Pengembalian extends Component
{
    public $data;

    public function mount()
    {
        $user = Auth::user();
        $this->data = $user->loans()->where('status', StatusLoanBookEnum::RETURNED->value)->get();
    }

    public function render()
    {
        return view('livewire.app.user.pengembalian.pengembalian', [
            'datas' => $this->data,
        ]);
    }
}
