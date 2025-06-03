<?php

namespace Database\Seeders;

use App\Models\BookLoanStats;
use Illuminate\Database\Seeder;

class BookLoanStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookLoanStats::factory()->count(2000)->create();
    }
}
