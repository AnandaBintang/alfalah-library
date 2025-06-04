<?php

namespace Database\Factories;

use App\Enum\ApprovalStatusEnum;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LoanExtensionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $previousDueDate = $this->faker->dateTimeBetween('-2 weeks', 'now');
        $newDueDate = (clone $previousDueDate)->modify('+7 days');

        return [
            'loan_id' => Loan::inRandomOrder()->first()?->id ?? Loan::factory(),
            'previous_due_date' => $previousDueDate,
            'new_due_date' => $newDueDate,
            'status' => $this->faker->randomElement([
                ApprovalStatusEnum::PENDING->value,
                ApprovalStatusEnum::APPROVED->value,
                ApprovalStatusEnum::REJECTED->value,
            ]),
        ];
    }
}
