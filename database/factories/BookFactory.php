<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'title' => $this->faker->word(),
      'subtitle' => $this->faker->sentence(),
      'isbn' => $this->faker->isbn13(),
      'stock' => $this->faker->randomNumber(),
      'rack_location' => $this->faker->numberBetween(1, 100),
    ];
  }
}
