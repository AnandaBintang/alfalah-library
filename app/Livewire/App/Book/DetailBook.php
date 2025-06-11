<?php

namespace App\Livewire\App\Book;

use App\Enum\StatusCartEnum;
use App\Enum\StatusCartItemEnum;
use App\Models\Book as ModelsBook;
use App\Models\Cart as ModelCart;
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
    $book = ModelsBook::find($bookId);

    if (!$book) {
      LivewireAlert::title('Error!')
        ->text('Buku tidak ditemukann.')
        ->position('center')
        ->timer(5500)
        ->error()
        ->show();
      return;
    }

    if ($book->stock < 1) {
      LivewireAlert::title('Error!')
        ->text('Buku out of stock.')
        ->position('center')
        ->timer(5500)
        ->error()
        ->show();
      return;
    }

    // Ambil keranjang user yang aktif (status PENDING)
    $cart = ModelCart::firstOrCreate(
      ['user_id' => auth()->id(), 'status' => StatusCartEnum::PENDING->value]
    );

    // Periksa apakah keranjang sudah memiliki item
    if ($cart->cartItem()->count() > 0) {
      LivewireAlert::title('Error!')
        ->text('Anda hanya dapat menambahkan satu buku ke keranjang.')
        ->position('center')
        ->timer(5500)
        ->error()
        ->show();
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
      ->timer(5500)
      ->success()
      ->show();
  }

  public function render()
  {
    return view('livewire.app.book.detail-book', [
      'data' => $this->book,
    ]);
  }
}
