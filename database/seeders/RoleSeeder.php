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
        Role::create(['name' => 'Direktur']);//1
        Role::create(['name' => 'Manager Keuangan']);//1
        Role::create(['name' => 'Manager Cabang']); //1
        Role::create(['name' => 'Manager Operasional']); //1

        Role::create(['name' => 'Accounting']); //1
        Role::create(['name' => 'KCA']); // multi kca
        Role::create(['name' => 'WKCA']); // multi
        Role::create(['name' => 'Technician']);
        Role::create(['name' => 'Stocker']); //1
        Role::create(['name' => 'Customer Service']); //1
        Role::create(['name' => 'NOC']); // multi
        Role::create(['name' => 'HR']); //1

        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdmin = User::where('name', 'Super Admin')->first();
        $superAdmin->assignRole($superAdminRole);
    }
}
