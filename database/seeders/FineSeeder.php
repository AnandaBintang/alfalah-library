<?php

namespace Database\Seeders;

use App\Models\Fine;
use Illuminate\Database\Seeder;

class FineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fine::factory()->count(100)->create();
    }
}
