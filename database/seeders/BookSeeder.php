<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $publisher = Publisher::all();
    Book::factory()
      ->count(100)
      ->create()
      ->each(function ($book) use ($publisher) {
        $book->publisher_id = $publisher->random()->id;
        $book->save();
      });


    // pdf book
      Book::create([
        'title' => 'Buku pdf',
        'subtitle' => 'Lorem ipsum dolor sit amet.',
        'is_ebook' => true,
        'isbn' => '1231313123123131',
        'publisher_id' => $publisher->random()->id,
        'stock' => 2,
        'rack_location' => 2,
        'cover_image_path' => 'cover-books/cover-book-1.jpg',
        'file_ebook_path' => 'ebooks/ebooks.pdf'
      ]);
  }
}
