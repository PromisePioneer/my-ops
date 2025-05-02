<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use App\Models\ItemCollection;
use App\Models\Master\Common\UnitType;
use Illuminate\Database\Seeder;

class ItemCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //kategori 1 aset
        ItemCollection::create([
            'name' => 'Kabel FO Aerial',
            'category_id' => ItemCategory::where('name', 'Kategori 1')->first()->id,
            'unit_type_id' => UnitType::where('name', 'Meter')->first()->id,
            'material' => 'Non Besi',
            'type' => 'ASET',
            'reorder_level' => 100,
        ]);


        ItemCollection::create([
            'name' => 'KU 96 Core',
            'category_id' => ItemCategory::where('name', 'Kategori 1')->first()->id,
            'unit_type_id' => UnitType::where('name', 'Meter')->first()->id,
            'material' => 'Non Besi',
            'type' => 'ASET',
            'reorder_level' => 100,
        ]);


        //kategori 1 jual
        ItemCollection::create([
            'name' => 'DW',
            'category_id' => ItemCategory::where('name', 'Kategori 1')->first()->id,
            'unit_type_id' => UnitType::where('name', 'Meter')->first()->id,
            'material' => 'Non Besi',
            'type' => 'Jual',
            'reorder_level' => 100,
        ]);

        //kategori 2 aset
        ItemCollection::create([
            'name' => 'Box ODC',
            'category_id' => ItemCategory::where('name', 'Kategori 2')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Non Besi',
            'type' => 'ASET',
            'reorder_level' => 100,
        ]);

        //kategori 2 jual
        ItemCollection::create([
            'name' => 'GPON',
            'category_id' => ItemCategory::where('name', 'Kategori 2')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Non besi',
            'type' => 'JUAL',
            'reorder_level' => 100,
        ]);


        //kategori 3 aset
        ItemCollection::create([
            'name' => 'Splicer',
            'category_id' => ItemCategory::where('name', 'Kategori 3')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Non besi',
            'type' => 'ASET',
            'reorder_level' => 100,
        ]);


        //kategori 4 aset
        ItemCollection::create([
            'name' => 'Pathcord',
            'category_id' => ItemCategory::where('name', 'Kategori 4')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Non besi',
            'type' => 'ASET',
            'reorder_level' => 100,
        ]);

        //kategori 4 jual
        ItemCollection::create([
            'name' => 'Pigtail',
            'category_id' => ItemCategory::where('name', 'Kategori 4')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'material' => 'Non besi',
            'type' => 'JUAL',
            'reorder_level' => 100,
        ]);

    }
}
