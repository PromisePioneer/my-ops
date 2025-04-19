<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemCategory::create([
            'name' => 'ASET',
        ]);

        ItemCategory::create([
            'name' => 'JUAL',
        ]);
    }
}
