<?php

namespace Database\Seeders;

use App\Models\JointClosureCode;
use Illuminate\Database\Seeder;

class JointClosureCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JointClosureCode::create([
            'branch_id' => 1,
            'code' => 123123213,
        ]);
    }
}
