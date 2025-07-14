<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Company;
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
        $mayatama = Company::where('code', "001")->first()->id;
        $psg = Company::where('code', "001")->first()->id;
        $linkkita = Company::where('code', "001")->first()->id;


        //kategori 1 aset
        ItemCollection::create([
            'code' => 'FO',
            'name' => 'Kabel FO Aerial',
            'unit_type_id' => UnitType::where('name', 'Meter')->first()->id,
            'type' => 'ASET',
            'category_id' => ItemCategory::where('name', 'Kategori 1')->first()->id,
            'asset_account_id' => Account::where('code', '126')->first()->id,
            'tangible_assets_type' => 'Bukan Bangunan',
            'non_building_group' => 'Kelompok I',
            'reorder_level' => 100,
            'must_have_code' => true,
        ]);


        ItemCollection::create([
            'name' => 'KU 96 Core',
            'code' => 'KU96',
            'category_id' => ItemCategory::where('name', 'Kategori 1')->first()->id,
            'unit_type_id' => UnitType::where('name', 'Meter')->first()->id,
            'type' => 'ASET',
            'asset_account_id' => Account::where('code', '126')->first()->id,
            'tangible_assets_type' => 'Bukan Bangunan',
            'non_building_group' => 'Kelompok I',
            'reorder_level' => 100,
            'must_have_code' => true,
        ]);


        //kategori 1 jual
        ItemCollection::create([
            'name' => 'DW',
            'code' => 'DW',
            'category_id' => ItemCategory::where('name', 'Kategori 1')->first()->id,
            'unit_type_id' => UnitType::where('name', 'Meter')->first()->id,
            'type' => 'JUAL',
            'tangible_assets_type' => null,
            'reorder_level' => 100,
            'must_have_code' => true,
        ]);

        //kategori 2 aset
        ItemCollection::create([
            'name' => 'Box ODC',
            'code' => 'ODC',
            'category_id' => ItemCategory::where('name', 'Kategori 2')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'type' => 'ASET',
            'tangible_assets_type' => 'Bukan Bangunan',
            'reorder_level' => 100,
            'non_building_group' => 'Kelompok I',
            'asset_account_id' => Account::where('code', '126')->first()->id,
            'must_have_code' => true,
        ]);

        //kategori 2 ASET
        ItemCollection::create([
            'name' => 'GPON',
            'code' => 'GPON',
            'category_id' => ItemCategory::where('name', 'Kategori 2')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'type' => 'ASET',
            'tangible_assets_type' => 'Bukan Bangunan',
            'non_building_group' => 'Kelompok I',
            'reorder_level' => 100,
            'asset_account_id' => Account::where('code', '126')->first()->id,
            'must_have_code' => true,
            'is_code_listed' => true,
        ]);


        ItemCollection::create([
            'name' => 'TANAH',
            'code' => 'TNH',
            'type' => 'ASET',
            'unit_type_id' => UnitType::where('name', 'Meter')->first()->id,
            'asset_account_id' => Account::where('code', '121')->first()->id,
            'tangible_assets_type' => 'Tanah',
        ]);


        //kategori 3 aset
        ItemCollection::create([
            'name' => 'Splicer',
            'code' => 'SPLICER',
            'category_id' => ItemCategory::where('name', 'Kategori 3')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'type' => 'ASET',
            'tangible_assets_type' => 'Bukan Bangunan',
            'non_building_group' => 'Kelompok I',
            'reorder_level' => 100,
            'asset_account_id' => Account::where('code', '126')->first()->id,
            'must_have_code' => true,
            'is_code_listed' => true,
        ]);


        //kategori 4 aset
        ItemCollection::create([
            'name' => 'Pathcord',
            'code' => 'PATHCORD',
            'category_id' => ItemCategory::where('name', 'Kategori 4')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'type' => 'JUAL',
            'tangible_assets_type' => null,
            'reorder_level' => 100,
        ]);

        //kategori 4 jual
        ItemCollection::create([
            'name' => 'Pigtail',
            'category_id' => ItemCategory::where('name', 'Kategori 4')->first()->id,
            'unit_type_id' => UnitType::where('name', 'PCS')->first()->id,
            'type' => 'JUAL',
            'tangible_assets_type' => null,
            'reorder_level' => 100,
        ]);

    }
}
