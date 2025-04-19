<?php

namespace Database\Seeders;

use App\Models\PtkpStatus;
use Illuminate\Database\Seeder;

class PtkpStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PtkpStatus::create([
            'name' => 'TK/0',
            'amount' => 54000000,
            'rate' => 0,
        ]);


        PtkpStatus::create([
            'name' => 'TK/1',
            'amount' => 58500000,
            'rate' => 0,
        ]);

        PtkpStatus::create([
            'name' => 'TK/2',
            'amount' => 63000000,
            'rate' => 0,
        ]);

        PtkpStatus::create([
            'name' => 'TK/3',
            'amount' => 67500000,
            'rate' => 0,
        ]);


        PtkpStatus::create([
            'name' => 'K/0',
            'amount' => 58500000,
            'rate' => 0,
        ]);

        PtkpStatus::create([
            'name' => 'K/1',
            'amount' => 63000000,
            'rate' => 0,
        ]);

        PtkpStatus::create([
            'name' => 'K/2',
            'amount' => 67500000,
            'rate' => 0,
        ]);

        PtkpStatus::create([
            'name' => 'K/3',
            'amount' => 72000000,
            'rate' => 0,
        ]);
    }
}
