<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
  public function show(Book $book)
  {
    // Validasi buku dan file ebook
    if (!$book->is_ebook || !$book->file_ebook_path) {
      abort(404, 'Ebook tidak tersedia.');
    }

    if (!Storage::disk('public')->exists($book->file_ebook_path)) {
      abort(404, 'File ebook tidak ditemukan.');
    }


    $pdfUrl = asset('storage/' . $book->file_ebook_path);


    return view('ebook.show', compact('book', 'pdfUrl'));
  }
}
