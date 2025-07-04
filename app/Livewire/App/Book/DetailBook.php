<?php

namespace App\Livewire\App\Book;

use App\Enum\StatusCartEnum;
use App\Enum\StatusCartItemEnum;
use App\Models\Book as ModelsBook;
use App\Models\Cart as ModelCart;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('livewire.layouts.main-app')]
#[Title('Detail Buku')]
class DetailBook extends Component
{
  public $id;

  public $book;

  public function mount($id)
  {
    $this->id = $id;
    $this->loadBook();
  }

  #[On('refreshDetailBook')]
  public function refreshDetailBook()
  {
    $this->loadBook();
  }

  protected function loadBook()
  {
    $this->book = ModelsBook::with('categories', 'publisher')->find($this->id);

    if (!$this->book) {
      abort(404);
    }
  }

  public function addToCart($bookId)
  {
    try {
      DB::beginTransaction();

      // Ambil data buku
      $book = ModelsBook::find($bookId);

      if (!$book) {
        LivewireAlert::title('Error!')
          ->text('Buku tidak ditemukan.')
          ->position('center')
          ->timer(5500)
          ->error()
          ->show();
        DB::rollBack();
        return;
      }

      // Cek stok buku
      if ($book->stock < 1) {
        LivewireAlert::title('Error!')
          ->text('Buku out of stock.')
          ->position('top-end')
          ->toast()
          ->timer(3000)
          ->error()
          ->show();
        DB::rollBack();
        return;
      }

      // Ambil keranjang user yang aktif (status PENDING)
      $cart = ModelCart::firstOrCreate(
        ['user_id' => auth()->id(), 'status' => StatusCartEnum::PENDING->value]
      );

      // Ambil item di keranjang
      $cartItems = $cart->cartItem()->get();

      // Cek jumlah buku di keranjang
      if ($cartItems->count() >= 2) {
        LivewireAlert::title('Error!')
          ->text('Anda hanya dapat meminjam maksimal dua buku.')
          ->position('top-end')
          ->timer(3000)
          ->toast()
          ->error()
          ->show();
        DB::rollBack();
        return;
      }

      // Cek apakah buku sudah ada di keranjang
      if ($cartItems->pluck('book_id')->contains($bookId)) {
        LivewireAlert::title('Error!')
          ->text('Buku ini sudah ada di keranjang.')
          ->position('top-end')
          ->timer(3000)
          ->toast()
          ->error()
          ->show();
        DB::rollBack();
        return;
      }

      // Tambahkan buku ke keranjang
      $cart->cartItem()->create([
        'book_id' => $book->id,
        'quantity' => 1,
        'status' => StatusCartItemEnum::BOOKED->value,
      ]);

      // Kurangi stok buku
      $book->decrement('stock');

      // Refresh detail buku dan tampilkan notifikasi sukses
      $this->dispatch('refreshDetailBook');
      LivewireAlert::title('Success!')
        ->text('Buku berhasil ditambahkan ke keranjang.')
        ->position('center')
        ->timer(6000)
        ->success()
        ->withCancelButton('Keranjang')
        ->onConfirm('backToCart')
        ->withConfirmButton('Daftar Buku')
        ->onConfirm('backToListBook')
        ->show();

      DB::commit();
    } catch (\Throwable $e) {


      LivewireAlert::title('Error!')
        ->text('Oops something went wrong. Please try again.')
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->error()
        ->show();
      DB::rollBack();
    }
  }


  public function backToListBook()
  {
    $this->redirectIntended(route('book.index'));
  }

  public function backToCart()
  {
    $this->redirectIntended(route('cart.index'));
  }


  public function render()
  {
    return view('livewire.app.book.detail-book', [
      'data' => $this->book,
    ]);
  }
}
