<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\InternetPackage;
use Illuminate\Database\Seeder;

class InternetPackageSeeder extends Seeder
{
    public function run(): void
    {

        $mayatama = Company::where('code', '001')->first()->id;

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER STARTER',
            'capacity' => 15,
            'price' => 166500,
        ]);
        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER BASIC',
            'capacity' => 20,
            'price' => 222000,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER BASIC PLUS',
            'capacity' => 25,
            'price' => 232100,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER HOME',
            'capacity' => 30,
            'price' => 333000,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER PRO',
            'capacity' => 45,
            'price' => 444000,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER ULTIMA',
            'capacity' => 60,
            'price' => 555000,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY CCTV BASIC PLUS',
            'capacity' => 75,
            'price' => 282600,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY CCTV HOME',
            'capacity' => 75,
            'price' => 383500,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER SILVER',
            'capacity' => 100,
            'price' => 252300,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER GOLD',
            'capacity' => 100,
            'price' => 353200,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER DIAMOND',
            'capacity' => 100,
            'price' => 454100,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER CCTV SILVER',
            'capacity' => 100,
            'price' => 302800,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER CCTV GOLD',
            'capacity' => 100,
            'price' => 403700,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER CCTV DIAMOND',
            'capacity' => 100,
            'price' => 504600,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER SOHO 10',
            'capacity' => 100,
            'price' => 1500000,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER SOHO 20',
            'capacity' => 100,
            'price' => 3000000,
        ]);

        InternetPackage::create([
            'company_id' => $mayatama,
            'name' => 'MY FIBER SOHO 30',
            'capacity' => 100,
            'price' => 4500000,
        ]);
    }
}
