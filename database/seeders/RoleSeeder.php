<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Direktur']);
        Role::create(['name' => 'Manager Keuangan']);
        Role::create(['name' => 'Kepala Cabang']);

        Role::create(['name' => 'Accountant']);
        Role::create(['name' => 'KCA']);
        Role::create(['name' => 'WKCA']);
        Role::create(['name' => 'Technician']);
        Role::create(['name' => 'Stocker']);
        Role::create(['name' => 'Customer Service']);
        Role::create(['name' => 'NOC']);


        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdmin = User::where('name', 'Super Admin')->first();
        $superAdmin->assignRole($superAdminRole);
    }
}
