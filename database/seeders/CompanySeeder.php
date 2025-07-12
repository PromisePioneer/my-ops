<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Storage;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'code' => '001',
            'name' => 'Mayatama Solusindo',
            'address' => 'Jalan Sultan Hasanuddin No. 8A Kelurahan Rimba Sekampung Kecamatan Dumai, Barat, Rimba Sekampung, Kec. Dumai Kota, Kota Dumai',
            'image' => Storage::disk('public')->url('companies/mayatama-logo.png'),
        ]);

        Company::create([
            'code' => '002',
            'name' => 'CV. Prestasi Sukses Gemilang',
        ]);


        Company::create([
            'code' => '003',
            'name' => 'PT. Linkkita Teknologi',
        ]);


    }
}
