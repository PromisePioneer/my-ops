<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'name' => 'GPON',
            'category_id' => ItemCategory::where('name', 'GPON')->first()->id,
            'need_sn' => true,
        ]);

        Item::create([
            'name' => 'Mikrotik',
            'category_id' => ItemCategory::where('name', 'Mikrotik')->first()->id,
            'need_sn' => true,
        ]);

        Item::create([
            'name' => 'CSR',
            'category_id' => ItemCategory::where('name', 'CSR')->first()->id,
            'need_sn' => true,
        ]);

        Item::create([
            'name' => 'Kabel',
            'category_id' => ItemCategory::where('name', 'Kabel')->first()->id,
            'need_sn' => false,
        ]);
    }
}
