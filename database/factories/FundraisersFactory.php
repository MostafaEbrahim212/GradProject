<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fundraisers>
 */
class FundraisersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl(),
            'goal' => $this->faker->randomFloat(2, 100, 1000),
            'raised' => $this->faker->randomFloat(2, 0, 100),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'account_number' => $this->faker->bankAccountNumber,
            'is_active' => $this->faker->boolean,
            'user_id' => 1,
            'category_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
