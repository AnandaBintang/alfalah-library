<?php

namespace App\Livewire\App\Book;

use App\Models\Book as ModelBook;
use App\Models\Category;
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
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        if ($this->category) {
            $query->whereHas('categories', fn ($q) => $q->where('id', $this->category));
        }

        $datas = $query->paginate(15);
        $categories = Category::orderBy('name')->get();

        return view('livewire.app.book.book', compact('datas', 'categories'));
    }
}
