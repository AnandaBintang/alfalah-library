<?php

namespace App\Livewire\App\Book;

use App\Models\Book as ModelBook;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
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
      $query->where('title', 'like', '%' . $this->search . '%');
    }

    if ($this->category) {
      $query->whereHas('categories', fn($q) => $q->where('id', $this->category));
    }

    if ($this->bookType === 'ebook') {
      $query->where('is_ebook', true);
    } elseif ($this->bookType === 'pear') {
      $query->where('is_ebook', false);
    }

    $datas = $query->paginate(15);
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
      ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%')
      )
      ->when($this->category, fn($q) => $q->whereHas('categories', fn($q2) => $q2->where('id', $this->category))
      )
      ->when($this->bookType === 'ebook', fn($q) => $q->where('is_ebook', true))
      ->when($this->bookType === 'pear', fn($q) => $q->where('is_ebook', false))
      ->with('categories')
      ->take(3)
      ->get();

    return view('livewire.app.book.book', compact('datas', 'categories', 'topBooks'));
  }
}
