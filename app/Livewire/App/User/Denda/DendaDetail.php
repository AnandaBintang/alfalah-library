<?php

namespace App\Livewire\App\User\Denda;

use App\Models\Fine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Denda')]
#[Layout('livewire.layouts.main-app')]
class DendaDetail extends Component
{
    public $data;

    public function mount($id)
    {
        $this->data = Fine::where('user_id', Auth::id())->where('id', $id)->first();
    }

    public function render()
    {
        return view('livewire.app.user.denda.denda-detail', [
            'fines' => $this->data,
        ]);
    }
}
