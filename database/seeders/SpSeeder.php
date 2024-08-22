<?php

namespace Database\Seeders;

use App\Models\SP;
use Illuminate\Database\Seeder;

class SpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SP::create([
            'branch_id' => 1,
            'user_id' => 2,
            'sp_number' => 'test',
            'sp_date' => date('y-m-d'),
            'sp_type' => 'SP-1',
            'created_by' => 1,
            'reason' => fake()->word(),
            'description' => fake()->word()
        ]);
    }
}
