<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemCategory::create([
            'name' => 'GPON'
        ]);

        ItemCategory::create([
            'name' => 'CORE 24'
        ]);

        ItemCategory::create([
            'name' => 'BEGEL'
        ]);

        ItemCategory::create([
            'name' => 'Sub Duct'
        ]);
    }
}
