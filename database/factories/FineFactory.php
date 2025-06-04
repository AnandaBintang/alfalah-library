<?php

namespace Database\Factories;

use App\Models\Fine;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FineFactory extends Factory
{
    protected $model = Fine::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['paid', 'unpaid']);
        $paidDate = $status === 'paid' ? $this->faker->date() : null;

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'loan_id' => Loan::inRandomOrder()->first()?->id,
            'amount' => $this->faker->randomFloat(2, 5, 100),
            'description' => $this->faker->sentence(),
            'status' => $status,
            'paid_date' => $paidDate,
        ];
    }
}
