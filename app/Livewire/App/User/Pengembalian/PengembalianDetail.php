<?php

namespace App\Livewire\App\User\Pengembalian;

use App\Enum\StatusLoanBookEnum;
use App\Models\Loan as LoanModel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Pengembalian')]
#[Layout('livewire.layouts.main-app')]
class PengembalianDetail extends Component
{
    public $data;

    public function mount($id)
    {
        $this->data = LoanModel::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', StatusLoanBookEnum::RETURNED->value)
            ->first();
    }

    public function render()
    {
        return view('livewire.app.user.pengembalian.pengembalian-detail');
    }
}
