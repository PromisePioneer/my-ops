<?php

namespace Database\Seeders;

use App\Models\Department;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');


        Department::create([
            'code' => '100',
            'name' => 'Managerial',
        ]);

        Department::create([
            'code' => '101',
            'name' => 'Finance',
        ]);

        Department::create([
            'code' => '102',
            'name' => 'Operational',
        ]);


        Department::create([
            'code' => '103',
            'name' => 'Warehouse',
        ]);


        Department::create([
            'code' => '104',
            'name' => 'NOC',
        ]);


        Department::create([
            'code' => '106',
            'name' => 'Customer Service',
        ]);

        Department::create([
            'code' => '107',
            'name' => 'KU',
        ]);


        Department::create([
            'code' => '108',
            'name' => 'Area',
        ]);
    }
}
