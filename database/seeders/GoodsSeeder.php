<?php

namespace Database\Seeders;

use App\Models\ItemCollection;
use App\Models\ItemCategory;
use App\Models\Master\Common\UnitType;
use Illuminate\Database\Seeder;

class GoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemCollection::create([
            'name' => 'GPON',
            'category_id' => ItemCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Besi'
        ]);

        ItemCollection::create([
            'name' => 'Mikrotik',
            'category_id' => ItemCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Besi'
        ]);

        ItemCollection::create([
            'name' => 'CSR',
            'category_id' => ItemCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Besi'

        ]);

        ItemCollection::create([
            'name' => 'Kabel',
            'category_id' => ItemCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Non Besi'
        ]);
    }
}
