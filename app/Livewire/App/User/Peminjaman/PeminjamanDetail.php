<?php

namespace App\Livewire\App\User\Peminjaman;

use App\Enum\StatusLoanBookEnum;
use App\Models\Loan as LoanModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Peminjaman')]
#[Layout('livewire.layouts.main-app')]
class PeminjamanDetail extends Component
{
    public $data;

    public function mount($id)
    {
        $this->data = LoanModel::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', StatusLoanBookEnum::BORROWED->value)
            ->first();
    }

    public function render()
    {
        return view('livewire.app.user.peminjaman.peminjaman-detail');
    }
}
