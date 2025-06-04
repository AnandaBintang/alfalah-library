<?php

namespace Database\Seeders;

use App\Models\VisitLog;
use Illuminate\Database\Seeder;

class VisitLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VisitLog::factory()->count(1500)->create();
    }
}
