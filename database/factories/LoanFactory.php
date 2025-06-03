<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $loanDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $loanDate)->modify('+14 days');

        $isReturned = $this->faker->boolean();

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'book_id' => Book::inRandomOrder()->first()?->id ?? Book::factory(),
            'loan_date' => $loanDate->format('Y-m-d'),
            'due_date' => $dueDate->format('Y-m-d'),
            'return_date' => $isReturned ? $this->faker->dateTimeBetween($loanDate, $dueDate)->format('Y-m-d') : null,
            'status' => $isReturned
              ? 'returned'
              : ($dueDate < now() ? 'overdue' : 'borrowed'),
        ];
    }
}
