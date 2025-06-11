<?php

namespace App\Livewire\App\Cart;

use App\Enum\StatusCartEnum;
use App\Enum\StatusCartItemEnum;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.main-app')]
class CartItem extends Component
{

  public $item;

  public function mount($cartItem)
  {
    $this->item = $cartItem->load('book');
  }

  public function increment()
  {
    DB::transaction(function () {
      $book = $this->item->book()->lockForUpdate()->first();

      if ($book->stock > 0) {
        $this->item->quantity++;
        $book->stock--;

        $book->save();
        $this->item->save();

        $this->dispatch('refreshCart');
      } else {
        LivewireAlert::title('Stock Habis!')
          ->text('Buku tidak tersedia.')
          ->position('center')
          ->timer(5500)
          ->error()
          ->show();
      }
    });
  }

  public function decrement()
  {
    DB::transaction(function () {
      $book = $this->item->book()->lockForUpdate()->first();

      if ($this->item->quantity > 1) {
        $this->item->quantity--;
        $book->stock++;

        $this->item->book->save();
        $this->item->save();
        $this->dispatch('refreshCart');
      }
    });

  }

  public function remove()
  {
    DB::transaction(function () {
      $book = $this->item->book()->lockForUpdate()->first();

      $this->item->update([
        'status' => StatusCartItemEnum::REJECTED->value,
      ]);

      $book->stock += $this->item->quantity;
      $book->save();

      $this->item->delete();

      $this->dispatch('refreshCart');


      LivewireAlert::title('Success!')
        ->text('Berhasil menghapus item.')
        ->position('center')
        ->timer(5500)
        ->success()
        ->show();
    });
  }

  public function confirmRemove(): void
  {
    LivewireAlert::title('Hapus buku?')
      ->text('Anda yakin ingin menghapus buku ini?')
      ->position('center')
      ->timer(5500)
      ->success()
      ->onConfirm('remove')
      ->withConfirmButton('Yes')
      ->withCancelButton('No')
      ->show();
  }

  public function render()
  {
    return view('livewire.app.cart.cart-item');
  }
}
