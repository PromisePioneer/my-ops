<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_id' => null,
            'name' => fake()->name,
            'code' => fake()->unique()->randomNumber(),
            'debit_balance' => 0,
            'credit_balance' => 0,
            'balance' => 0,
        ];
    }
}
