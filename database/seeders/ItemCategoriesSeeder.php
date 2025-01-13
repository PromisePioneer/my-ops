<?php

namespace Database\Seeders;

use App\Models\GoodsCategory;
use Illuminate\Database\Seeder;

class ItemCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GoodsCategory::create([
            'name' => 'ASET',
        ]);

        GoodsCategory::create([
            'name' => 'JUAL',
        ]);
    }
}
