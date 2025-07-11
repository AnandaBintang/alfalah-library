<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookCardService;
use Illuminate\Http\Request;

class BookCardController extends Controller
{
  public function printCard(Book $book)
  {
    $cardData = BookCardService::generateCardData($book);
    return view('book-card.print', compact('cardData'));
  }

  public function printCardsBulk(Request $request)
  {
    $bookIds = explode(',', $request->ids);

    if (count($bookIds) > 30) {
      return redirect()->back()->with('error', 'Maksimal 30 buku yang dapat dicetak sekaligus.');
    }

    $books = Book::with(['writer', 'publisher'])->whereIn('id', $bookIds)->get();
    $cardsData = $books->map(fn($book) => BookCardService::generateCardData($book));

    return view('book-card.print-bulk', compact('cardsData'));
  }

  public function generateCard(Book $book)
  {
    $cardData = BookCardService::generateCardData($book);
    $libraryCardCode = BookCardService::generateLibraryCardCode($book);

    return response()->json([
      ...$cardData,
      'library_card_code' => $libraryCardCode,
    ]);
  }
}
