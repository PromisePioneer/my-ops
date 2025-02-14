<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Branch;
use App\Models\Department;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Area::create([
            'department_id' => Department::where('name', 'Vendor')->first()->id,
            'branch_id' => Branch::where('name', 'Pekanbaru Arifin')->first()->id,
            'name' => 'VDR ' . Branch::where('name', 'Pekanbaru Arifin')->first()?->name . '01',
        ]);

        Area::create([
            'department_id' => Department::where('name', 'Vendor')->first()->id,
            'branch_id' => Branch::where('name', 'Pekanbaru Arifin')->first()->id,
            'name' => 'VDR ' . Branch::where('name', 'Pekanbaru Arifin')->first()?->name . '02',
        ]);
    }
}
