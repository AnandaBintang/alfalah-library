<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $publisher = Publisher::all();
        $books = Book::factory()
            ->count(100)
            ->create()
            ->each(function ($book) use ($publisher) {
                $book->publisher_id = $publisher->random()->id;
                $book->save();
            });

        $bukutestCategory = Category::create([
            'name' => 'bukutest',
        ]);

        $megalodon = Book::create([
            'title' => 'Megalodon',
            'subtitle' => 'Megalodon',
            'isbn' => 1231314,
            'stock' => 3,
            'rack_location' => 29,
            'cover_image_path' => 'cover-books/cover-book-1.jpg',
        ]);

        $megalodon->categories()->attach($bukutestCategory->id);

        $categories = Category::factory()->count(10)->create();

        // Pasangkan kategori ke setiap buku (pivot seeding)
        foreach ($books as $book) {
            // Assign 1–3 kategori secara acak ke setiap buku
            $book->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
