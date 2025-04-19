<?php

namespace Database\Seeders;

use App\Models\Master\Common\ServiceCategory;
use Illuminate\Database\Seeder;

class ServicesCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            'Internet Dedicated',
            'Internet SOHO',
            'Metro-E',
            'Local Loop',
            'Internet Broadband',
        ];

        foreach ($services as $service) {
            ServiceCategory::create([
                'name' => $service,
            ]);
        }
    }
}
