<?php

namespace Database\Seeders;

use App\Models\MenuSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        MenuSection::create([
            'name' => 'Dashboard',
        ]);

        MenuSection::create([
            'name' => 'Master Data',
        ]);


        MenuSection::create([
            'name' => 'Inventory',
        ]);


        MenuSection::create([
            'name' => 'Jurnal',
        ]);


        MenuSection::create([
            'name' => 'Transaksi',
        ]);


        MenuSection::create([
            'name' => 'Utilitas',
        ]);


        MenuSection::create([
            'name' => 'Manajemen Karyawan',
        ]);


    }
}
