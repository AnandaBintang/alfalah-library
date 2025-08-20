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
//use App\Trait\NotificationsAndDialog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    DB::beginTransaction();
    try {
      $user = Auth::user();
      Log::info('Checkout started', ['user_id' => $user->id]);

      $hasUnpaidFine = $user->fines()->where('status', 'unpaid')->exists();
      Log::info('Has unpaid fine?', ['value' => $hasUnpaidFine]);

      if ($hasUnpaidFine) {
        DB::rollBack();
        Log::info('Checkout failed: unpaid fines');
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
      Log::info('Has pending loan request?', ['value' => $isUserHaveRequestedBooks]);

      if ($isUserHaveRequestedBooks) {
        DB::rollBack();
        Log::info('Checkout failed: pending loan request');
        LivewireAlert::title('Checkout gagal!')
          ->text('Anda masih memiliki permintaan peminjaman buku!')
          ->position('center')
          ->timer(5500)
          ->error()
          ->show();
        return;
      }

      $hasBookedBook = $user->loans()->whereNull('return_date')->exists();
      Log::info('Has unreturned books?', ['value' => $hasBookedBook]);

      if ($hasBookedBook) {
        DB::rollBack();
        Log::info('Checkout failed: unreturned books');
        LivewireAlert::title('Checkout gagal!')
          ->text('Anda masih memiliki buku yang belum dikembalikan.')
          ->position('center')
          ->timer(5500)
          ->error()
          ->show();
        return;
      }

      if (!$this->cart || $this->cartItems->isEmpty()) {
        DB::rollBack();
        Log::info('Checkout failed: cart empty');
        LivewireAlert::title('Error!')
          ->text('Keranjang kosong atau tidak ditemukan.')
          ->position('center')
          ->timer(5500)
          ->error()
          ->show();
        return;
      }

      $this->cart->update(['status' => StatusCartEnum::CHECK_OUT->value]);
      Log::info('Cart status updated to CHECK_OUT', ['cart_id' => $this->cart->id]);

      foreach ($this->cartItems as $item) {
        $item->status = StatusCartItemEnum::APPROVED->value;
        $item->save();
        Log::info('Cart item approved', ['cart_item_id' => $item->id]);

        LoanModel::create([
          'user_id' => $user->id,
          'book_id' => $item->book_id,
          'loan_date' => now(),
          'due_date' => now()->addDays(7),
          'loan_status' => StatusLoanBookEnum::PENDING->value,
          'confirmation_status' => ConfirmationStatusLoanEnum::PENDING->value,
          'timeline_status' => TimelineStatusEnum::PENDING->value,
        ]);
        Log::info('Loan created', ['book_id' => $item->book_id, 'user_id' => $user->id]);
      }

      DB::commit();
      Log::info('Checkout successful', ['user_id' => $user->id]);

      $this->dispatch('refreshCart');

      LivewireAlert::title('Success!')
        ->text('Berhasil melakukan checkout.')
        ->position('center')
        ->timer(5500)
        ->withConfirmButton('Ok')
        ->onConfirm('backToIndexBook')
        ->success()
        ->show();
    } catch (\Throwable $e) {
      DB::rollBack();
      Log::channel('errorlog')->error('Checkout failed: ' . $e->getMessage());
      LivewireAlert::title('Error!')
        ->text('Hubungi admin untuk informasi lebih lanjut')
        ->position('center')
        ->timer(5500)
        ->withConfirmButton('Ok')
        ->error()
        ->show();
    }
  }

  public function backToIndexBook()
  {
    $this->redirect(route('book.index'));
  }


  public function render()
  {
    return view('livewire.app.cart.cart', [
      'cartItems' => $this->cartItems,
    ]);
  }
}
