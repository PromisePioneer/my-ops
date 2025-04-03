<?php

namespace Database\Seeders;

use App\Models\AccountCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aktiva = AccountCategory::create([
            'name' => "Aktiva"
        ]);


        $passiva = AccountCategory::create([
            'name' => "Passiva"
        ]);

        AccountCategory::create([
            'name' => 'Aset Tetap',
            'parent_id' => $aktiva->id
        ]);

        AccountCategory::create([
            'name' => 'Aset Lancar',
            'parent_id' => $aktiva->id
        ]);


        AccountCategory::create([
            'name' => 'Utang Lancar',
            'parent_id' => $passiva->id
        ]);


        AccountCategory::create([
            'name' => 'Utang Jangka Panjang',
            'parent_id' => $passiva->id
        ]);


        AccountCategory::create([
            'name' => 'Modal',
            'parent_id' => $passiva->id
        ]);
    }
}
