<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Warehouse::create([
            'code' => 'GP',
            'name' => 'Gudang Pusat'
        ]);

        Warehouse::create([
            'code' => 'DP',
            'name' => 'Dumai Pusat'
        ]);
    }
}
