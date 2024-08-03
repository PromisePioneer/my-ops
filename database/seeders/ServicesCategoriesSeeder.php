<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
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
            'Internet Broadband'
        ];

        $capacities = [
            1, 5, 10, 15, 20, 30, 40, 50, 100, 200, 300, 400, 500, 1000, 2000, 3000, 4000, 5000, 10000, 50000
        ];



        foreach ($services as $service){
            foreach ($capacities as $capacity) {
                ServiceCategory::create([
                    'name' => $service,
                    'capacity' => $capacity
                ]);
            }
        }
    }
}
