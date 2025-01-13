<?php

namespace Database\Seeders;

use App\Models\Goods;
use App\Models\GoodsCategory;
use App\Models\UnitType;
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
            'need_sn' => true,
        ]);

        Goods::create([
            'name' => 'Mikrotik',
            'category_id' => GoodsCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'need_sn' => true,
        ]);

        Goods::create([
            'name' => 'CSR',
            'category_id' => GoodsCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'need_sn' => true,
        ]);

        Goods::create([
            'name' => 'Kabel',
            'category_id' => GoodsCategory::where('name', 'ASET')->first()->id,
            'unit_type_id' => UnitType::where('name', 'ROLL')->first()->id,
            'need_sn' => false,
        ]);
    }
}
