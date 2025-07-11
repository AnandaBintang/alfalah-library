<?php

namespace App\Livewire\App\Cart;

use App\Enum\ConfirmationStatusLoanEnum;
use App\Enum\StatusCartEnum;
use App\Enum\StatusCartItemEnum;
use App\Enum\StatusLoanBookEnum;
use App\Enum\TimelineStatusEnum;
use App\Models\Cart as CartModel;
use App\Models\CartItem as CartItemModel;
use App\Models\Loan as LoanModel;
use App\Trait\NotificationsAndDialog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


#[Layout('livewire.layouts.main-app')]
#[Title('Keranjang')]
class Cart extends Component
{

  public $cart;

  public $cartItems;

  public function mount()
  {
    $this->loadCart();
  }

  #[On('refreshCart')]
  public function loadCart()
  {
    $this->cart = CartModel::where('user_id', Auth::id())
      ->where('status', StatusCartEnum::PENDING->value)
      ->first();

    $this->cartItems = CartItemModel::with(['book', 'cart'])
      ->whereHas('cart', function ($query) {
        $query->where('user_id', Auth::id())
          ->where('status', StatusCartEnum::PENDING->value);
      })
      ->where('status', StatusCartItemEnum::BOOKED->value)
      ->get();
  }

  public function checkout()
  {
    $user = Auth::user();

    $hasUnpaidFine = $user->fines()->where('status', 'unpaid')->exists();
    if ($hasUnpaidFine) {
      LivewireAlert::title('Checkout Gagal!')
        ->text('Anda masih memiliki denda yang belum dibayar.')
        ->position('center')
        ->timer(5500)
        ->error()
        ->show();
      return;
    }

    $isUserHaveRequestedBooks = $user->loans()->where(function ($query) {
      $query->where('loan_status', StatusLoanBookEnum::PENDING->value);
      $query->where('confirmation_status', ConfirmationStatusLoanEnum::PENDING->value);
    })->exists();

    if ($isUserHaveRequestedBooks) {
      LivewireAlert::title('Checkout gagal!')
        ->text('Anda masih memiliki permintaan peminjaman buku!')
        ->position('center')
        ->timer(5500)
        ->error()
        ->show();
      return;
    }

    $hasBookedBook = $user->loans()->whereNull('return_date')->exists();
    if ($hasBookedBook) {
      LivewireAlert::title('Checkout gagal!')
        ->text('Anda masih memiliki buku yang belum dikembalikan.')
        ->position('center')
        ->timer(5500)
        ->error()
        ->show();
      return;
    }

    if (!$this->cart || $this->cartItems->isEmpty()) {
      LivewireAlert::title('Error!')
        ->text('Keranjang kosong atau tidak ditemukan.')
        ->position('center')
        ->timer(5500)
        ->error()
        ->show();
      return;
    }

    $this->cart->update([
      'status' => StatusCartEnum::CHECK_OUT->value,
    ]);

    foreach ($this->cartItems as $item) {
      $item->status = StatusCartItemEnum::APPROVED->value;
      $item->save();

      LoanModel::create([
        'user_id' => $user->id,
        'book_id' => $item->book_id,
        'loan_date' => now(),
        'due_date' => now()->addDays(7),
        'loan_status' => StatusLoanBookEnum::PENDING->value,
        'confirmation_status' => ConfirmationStatusLoanEnum::PENDING->value,
        'timeline_status' => TimelineStatusEnum::PENDING->value,
      ]);
    }

    $this->dispatch('refreshCart');
    $this->redirect(route('book.index'));
    LivewireAlert::title('Success!')
      ->text('Berhasil melakukan checkout.')
      ->position('center')
      ->timer(5500)
      ->success()
      ->show();
  }

  public function render()
  {
    return view('livewire.app.cart.cart', [
      'cartItems' => $this->cartItems,
    ]);
  }
}
