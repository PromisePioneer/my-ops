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
            'code' => 'AM011748',
        ]);
    }
}
