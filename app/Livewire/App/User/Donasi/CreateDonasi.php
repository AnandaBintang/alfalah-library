<?php

namespace App\Livewire\App\User\Donasi;

use App\Models\Donation;
use App\Trait\NotificationsAndDialog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Donasi Baru')]
#[Layout('livewire.layouts.main-app')]
class CreateDonasi extends Component
{
    use NotificationsAndDialog, WithFileUploads;

    public $item_name;

    public $description;

    public $quantity = 1;

    public $image;

    protected $rules = [
        'item_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'quantity' => 'required|integer|min:1',
        'image' => 'nullable|image|max:5120',
    ];

    public function submit()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->storeAs(
                'donations',
                Str::random(30).'.'.$this->image->getClientOriginalExtension(),
                'public'
            );
        }

        Donation::create([
            'user_id' => Auth::id(),
            'item_name' => $this->item_name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'donation_date' => now(),
            'image' => $imagePath,
        ]);

        $this->reset();
        $this->redirect(route('donations.index'));
        $this->successNotification('Donasi Dikirim', 'Permintaan donasi berhasil dikirim dan menunggu persetujuan.');
    }

    public function render()
    {
        return view('livewire.app.user.donasi.create-donasi');
    }
}
