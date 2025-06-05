<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VisitLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class VisitLogFactory extends Factory
{
    protected $model = VisitLog::class;

    public function definition(): array
    {
        // Ambil ID user secara acak
        $userId = User::inRandomOrder()->value('id');

        $monthOffset = rand(0, 2);
        $startOfMonth = Carbon::now()->subMonths($monthOffset)->startOfMonth();
        $endOfMonth = (clone $startOfMonth)->endOfMonth();

        $checkIn = $this->faker->dateTimeBetween($startOfMonth, $endOfMonth);
        $checkOut = (clone $checkIn)->modify('+'.rand(1, 4).' hours');

        return [
            'user_id' => $userId,
            'check_in_time' => $checkIn,
            'check_out_time' => $this->faker->boolean(80) ? $checkOut : null,
            'purpose' => $this->faker->randomElement([
                'Membaca buku',
                'Belajar kelompok',
                'Diskusi tugas',
                'Mengakses komputer',
                'Bertanya ke pustakawan',
            ]),
        ];
    }
}
