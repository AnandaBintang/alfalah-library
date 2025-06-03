<?php

namespace Database\Seeders;

use App\Models\LoanExtension;
use Illuminate\Database\Seeder;

class LoanExtensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoanExtension::factory()->count(20)->create();
    }
}
