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
            ->count(50)
            ->create()
            ->each(function ($book) use ($publisher) {
                $book->publisher_id = $publisher->random()->id;
                $book->save();
            });
    }
}
