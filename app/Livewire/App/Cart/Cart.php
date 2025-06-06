<?php

namespace App\Livewire\App\Cart;

use App\Enum\StatusCartEnum;
use App\Enum\StatusLoanBookEnum;
use App\Models\Cart as CartModel;
use App\Models\Loan as LoanModel;
use App\Trait\NotificationsAndDialog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.main-app')]
#[Title('Keranjang')]
class Cart extends Component
{
    use NotificationsAndDialog;

    public $cart;

    public function mount()
    {
        $this->loadCart();
    }

    #[On('refreshCart')]
    public function loadCart()
    {
        $this->cart = CartModel::with('cartItem.book')
            ->where('user_id', Auth::id())
            ->where('status', StatusCartEnum::PENDING->value)
            ->first();
    }

    public function checkout()
    {
        $user = Auth::user();

        $hasUnpaidFine = $user->fines()->where('status', 'unpaid')->exists();
        if ($hasUnpaidFine) {
            $this->errorNotification('Checkout Gagal', 'Anda masih memiliki denda yang belum dibayar.');

            return;
        }

        $this->cart = CartModel::with('cartItem.book')
            ->where('user_id', $user->id)
            ->where('status', StatusCartEnum::PENDING->value)
            ->first();

        if (! $this->cart || $this->cart->cartItem->isEmpty()) {
            $this->errorNotification('Error', 'Keranjang kosong atau tidak ditemukan.');

            return;
        }

        $this->cart->update([
            'status' => StatusCartEnum::CHECK_OUT->value,
        ]);

        foreach ($this->cart->cartItem as $item) {
            LoanModel::create([
                'user_id' => $user->id,
                'book_id' => $item->book_id,
                'loan_date' => now(),
                'due_date' => now()->addDays(7),
                'status' => StatusLoanBookEnum::BORROWED->value,
            ]);
        }

        $this->dispatch('refreshCart');
        $this->successNotification('Success', 'Berhasil melakukan checkout.');
        $this->redirect(route('book.index'));
    }

    public function render()
    {
        return view('livewire.app.cart.cart', [
            'data' => $this->cart,
        ]);
    }
}
