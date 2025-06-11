<?php

namespace App\Livewire\App\Book;

use App\Enum\StatusCartEnum;
use App\Enum\StatusCartItemEnum;
use App\Models\Book as ModelsBook;
use App\Models\Cart as ModelCart;
use App\Trait\NotificationsAndDialog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.main-app')]
#[Title('Detail Buku')]
class DetailBook extends Component
{
  use NotificationsAndDialog;

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
    $book = ModelsBook::find($bookId);

    if (!$book) {
      $this->errorNotification('Error!', 'Buku tidak ditemukan.');
      return;
    }

    if ($book->stock < 1) {
      $this->errorNotification('Error!', 'Buku out of stock.');
      return;
    }

    // Ambil keranjang user yang aktif (status PENDING)
    $cart = ModelCart::firstOrCreate(
      ['user_id' => auth()->id(), 'status' => StatusCartEnum::PENDING->value]
    );

    // Periksa apakah keranjang sudah memiliki item
    if ($cart->cartItem()->count() > 0) {
      $this->errorNotification('Error!', 'Anda hanya dapat menambahkan satu buku ke keranjang.');
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
    $this->successNotification('Success!', 'Buku berhasil ditambahkan ke keranjang.');
  }

  public function render()
  {
    return view('livewire.app.book.detail-book', [
      'data' => $this->book,
    ]);
  }
}
