<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
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
            'full_name' => fake()->name,
            'company_name' => fake()->company,
            'email' => fake()->unique(true)->safeEmail(),
            'phone_number' => fake()->phoneNumber,
            'identity_type' => 'ktp',
            'identity_number' => fake()->unique(true)->numberBetween($min = 1, $max = 999999999999999999),
            'fax' => fake()->phoneNumber,
            'npwp' => fake()->randomNumber(),
            'complete_address' => fake()->address,
            'other_info' => fake()->text,
        ];
    }
}
