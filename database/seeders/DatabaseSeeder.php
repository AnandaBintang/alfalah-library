<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PublisherSeeder::class,
            BookSeeder::class,
                        LoanSeeder::class,
                        LoanExtensionSeeder::class,
                        FineSeeder::class,
                        BookLoanStatsSeeder::class,
                        DonationSeeder::class,
            CategorySeeder::class,
                        VisitLogSeeder::class,
        ]);
    }
}
