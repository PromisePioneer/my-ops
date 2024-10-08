<?php

namespace Database\Seeders;

use App\Models\ODPArea;
use Illuminate\Database\Seeder;

class ODPAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ODPArea::create([
            'branch_id' => 1,
            'code' => 'AM011748',
        ]);
    }
}
