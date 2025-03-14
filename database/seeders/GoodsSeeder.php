<?php

namespace Database\Seeders;

use App\Models\Goods;
use App\Models\GoodsCategory;
use App\Models\Master\Common\UnitType;
use Illuminate\Database\Seeder;

class GoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Goods::create([
            'name' => 'GPON',
            'category_id' => GoodsCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Besi'
        ]);

        Goods::create([
            'name' => 'Mikrotik',
            'category_id' => GoodsCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Besi'
        ]);

        Goods::create([
            'name' => 'CSR',
            'category_id' => GoodsCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Besi'

        ]);

        Goods::create([
            'name' => 'Kabel',
            'category_id' => GoodsCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Non Besi'
        ]);
    }
}
