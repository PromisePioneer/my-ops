<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\UnitType;
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
            'category_id' => ItemCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'need_sn' => true,
        ]);

        Item::create([
            'name' => 'Mikrotik',
            'category_id' => ItemCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'need_sn' => true,
        ]);

        Item::create([
            'name' => 'CSR',
            'category_id' => ItemCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'need_sn' => true,
        ]);

        Item::create([
            'name' => 'Kabel',
            'category_id' => ItemCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'ROLL')->first()->id,
            'need_sn' => false,
        ]);
    }
}
