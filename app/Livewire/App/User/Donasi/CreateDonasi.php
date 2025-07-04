<?php

namespace App\Livewire\App\User\Donasi;

use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Donasi Baru')]
#[Layout('livewire.layouts.main-app')]
class CreateDonasi extends Component
{
  use WithFileUploads;

  public $item_name;

  public $description;

  public $quantity = 1;

  public $image;

  protected $rules = [
    'item_name' => 'required|string|max:255',
    'description' => 'nullable|string|max:1000',
    'quantity' => 'required|integer|min:1',
    'image' => 'required|image|max:5120',
  ];

  public function submit()
  {
    try {
      DB::beginTransaction();
      $this->validate();

      $imagePath = null;
      if ($this->image) {
        $filename = Str::random(30) . '.' . $this->image->getClientOriginalExtension();

        $imagePath = Storage::disk('public')->putFileAs(
          'donations',
          $this->image,
          $filename
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

      $this->redirect(route('donasi.store'));
      LivewireAlert::title('Donasi Baru!')
        ->text('Permintaan donasi berhasil dikirim dan menunggu persetujuan.')
        ->position('center')
        ->timer(5500)
        ->success()
        ->show();
      DB::commit();
    } catch (\Throwable $e) {
      DB::rollBack();
      LivewireAlert::title('Donasi Gagal!')
        ->error()
        ->text($e->getMessage())
        ->timer(2000)
        ->show();
    }

  }

  public function render()
  {
    return view('livewire.app.user.donasi.create-donasi');
  }
}
