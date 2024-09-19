<?php

namespace Database\Seeders;

use App\Models\TaxSetting;
use Illuminate\Database\Seeder;

class TaxSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaxSetting::create([
            'name' => 'PPN',
            'rate' => 12,
        ]);

        TaxSetting::create([
            'name' => 'PPH 21',
            'rate' => 2,
        ]);
    }
}
