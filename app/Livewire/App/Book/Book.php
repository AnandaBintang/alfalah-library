<?php

namespace App\Livewire\App\Book;

use App\Models\Book as ModelBook;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Title('Buku')]
#[Layout('livewire.layouts.main-app')]
class Book extends Component
{
  use WithoutUrlPagination, WithPagination;

  public $search = '';

  public $category = '';

  public $inputCategory = '';

  public string $bookType = 'ebook';

  public function mount()
  {
    $user = Auth::user();

    if (!$user->hasVerifiedEmail()) {

      $user->sendEmailVerificationNotification();

      LivewireAlert::title('Verifikasi Email Diperlukan')
        ->text('Silakan cek email kamu untuk verifikasi. Jika tidak menemukan emailnya, klik tombol di halaman profil untuk mengirim ulang.')
        ->warning()
        ->timer(5000)
        ->withConfirmButton('Oke')
        ->show();
    }
  }


  public function updating($property)
  {
    if ($property === 'search') {
      $this->resetPage();
    }
  }

  public function filterCategory()
  {
    $this->category = $this->inputCategory;
    $this->resetPage();
  }

  public function render()
  {
    $query = ModelBook::query()->with('categories');

    if ($this->search) {
      $this->resetPage();
      $query->where('title', 'like', '%' . $this->search . '%');
    }

    if ($this->category) {
      $query->whereHas('categories', fn($q) => $q->where('id', $this->category));
    }

    if ($this->bookType === 'ebook') {
      $query->where('is_ebook', 1);
    } elseif ($this->bookType === 'pear') {
      $query->where('is_ebook', 0);
    }

    $datas = $query->cursorPaginate(15);
    $categories = Category::orderBy('name')->get();

    // Ambil top 10 book_id yang paling sering dipinjam (untuk keperluan filter)
    $topLoanedIds = DB::table('loans')
      ->select('book_id', DB::raw('count(*) as total'))
      ->where('loan_status', '!=', 'REJECTED')
      ->groupBy('book_id')
      ->orderByDesc('total')
      ->limit(10)
      ->pluck('book_id');

    // Ambil hanya 3 top books sesuai filter aktif
    $topBooks = ModelBook::query()
      ->whereIn('id', $topLoanedIds)
      ->when($this->bookType === 'ebook', fn($q) => $q->where('is_ebook', 1))
      ->when($this->bookType === 'pear', fn($q) => $q->where('is_ebook', 0))
      ->with('categories')
      ->take(3)
      ->get();

    return view('livewire.app.book.book', compact('datas', 'categories', 'topBooks'));
  }
}
