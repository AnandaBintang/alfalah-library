<?php

namespace App\Livewire\App\User\Donasi;

use App\Models\Donation as DonationModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Donasi')]
#[Layout('livewire.layouts.main-app')]
class DonasiDetail extends Component
{
    public $data;

    public function mount($id)
    {
        $this->data = DonationModel::where('user_id', Auth::id())->where('id', $id)->first();
    }

    public function render()
    {
        return view('livewire.app.user.donasi.donasi-detail', [
            'datas' => $this->data,
        ]);
    }
}
