<?php

namespace App\Livewire\App\Book;

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

        if (! $this->book) {
            abort(404);
        }
    }

    public function addToCart($bookId)
    {
        $book = ModelsBook::find($bookId);

        if ($book->stock < 1) {
            $this->errorNotification('Error!', 'Buku out of stock.');

            return;
        }

        // Ambil atau buat keranjang user ini
        $cart = ModelCart::create(['user_id' => auth()->id()]);

        $isBookExistInCart = $cart->cartItem()->where('book_id', $bookId)->first();

        if ($isBookExistInCart) {
            $isBookExistInCart->increment('quantity');
        } else {
            $cart->cartItem()->create([
                'book_id' => $book->id,
                'quantity' => 1,
            ]);
        }

        $book->decrement('stock');
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
