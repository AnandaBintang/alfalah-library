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
