<?php

namespace Database\Factories;

use App\Enum\StatusLoanBookEnum;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    public function definition(): array
    {
        $loanDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $dueDate = (clone $loanDate)->modify('+14 days');

        $status = $this->faker->randomElement([
            StatusLoanBookEnum::BORROWED,
            StatusLoanBookEnum::RETURNED,
        ]);

        $returnDate = null;
        if ($status === StatusLoanBookEnum::RETURNED) {
            $returnDate = $this->faker->dateTimeBetween($loanDate, 'now')->format('Y-m-d');
        }

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'book_id' => Book::inRandomOrder()->first()?->id ?? Book::factory()->create()->id,
            'loan_date' => $loanDate->format('Y-m-d'),
            'due_date' => $dueDate->format('Y-m-d'),
            'return_date' => $returnDate,
            'status' => $status->value,
        ];
    }
}
