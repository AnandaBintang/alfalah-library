<?php

namespace App\Livewire\App\Cart;

use App\Enum\StatusCartEnum;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.main-app')]
class CartItem extends Component
{
    use WireUiActions;

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
                $this->notification()->send([
                    'title' => 'Stok Habis',
                    'description' => 'Buku tidak tersedia.',
                    'icon' => 'error',
                ]);
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
                'status' => StatusCartEnum::CANCELLED->value,
            ]);

            $book->stock += $this->item->quantity;
            $book->save();

            $this->item->delete();

            $this->dispatch('refreshCart');
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success',
                'description' => 'Berhasil menghapus item.',
            ]);
        });
    }

    public function confirmRemove(): void
    {
        $this->notification()->confirm([
            'title' => 'Delete Item?',
            'description' => 'Apakah anda yakin ingin menghapus item ini?',
            'acceptLabel' => 'Yes',
            'method' => 'remove',
            'params' => 'Saved',
        ]);
    }

    public function render()
    {
        return view('livewire.app.cart.cart-item');
    }
}
