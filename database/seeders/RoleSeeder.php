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
        // Managerial
        Role::create(['name' => 'Main Commissioner']);
        $directorRole = Role::create(['name' => 'Director']);
        $generalManagerRole = Role::create(['name' => 'General Manager']);
        $financeManagerRole = Role::create(['name' => 'FA & Tax Manager']);
        $operationalManagerRole = Role::create(['name' => 'Operational Manager']);
        $branchManagerRole = Role::create(['name' => 'Branch Manager']);

        //Finance
        Role::create(['name' => 'Tax Admin Supervisor']);
        Role::create(['name' => 'Billing Admin Supervisor']);
        Role::create(['name' => 'Inventory Controller Supervisor']);
        Role::create(['name' => 'Customer Payment Supervisor']);
        Role::create(['name' => 'Finance & Accounting Supervisor']);
        Role::create(['name' => 'FA Senior Staff']);
        Role::create(['name' => 'Finance & Accounting Staff']);

        // Operational
        Role::create(['name' => 'Legal & Corporate Commissioner']);
        Role::create(['name' => 'HR & Operational Staff']);
        Role::create(['name' => 'Backbone Team Supervisor']);
        Role::create(['name' => 'Stocker Supervisor']);
        Role::create(['name' => 'Project Controller & Vendor Supervisor']);
        Role::create(['name' => 'Quality Controller Supervisor']);
        Role::create(['name' => 'Trainer & Quality Control Staff']);
        Role::create(['name' => 'Quality Control Staff']);
        Role::create(['name' => 'After Sales Customer Service']);
        Role::create(['name' => 'Stocker Staff']);


        //Warehouse
        Role::create(['name' => 'Welding Senior Engineer']);
        Role::create(['name' => 'Electrical Senior Engineer']);
        Role::create(['name' => 'Warehouse Security']);
        Role::create(['name' => 'Warehouse Stocker Staff']);


        //NOC
        Role::create(['name' => 'NOC Supervisor']);
        Role::create(['name' => 'NOC Staff']);


        //Customer Service
        Role::create(['name' => 'Customer Service Leader']);
        Role::create(['name' => 'Customer Service Staff']);


        //KU
        Role::create(['name' => 'KU Head Engineer']);
        Role::create(['name' => 'KU Senior Engineer']);
        Role::create(['name' => 'KU Engineer']);

        //area
        Role::create(['name' => 'Head Engineer']);
        Role::create(['name' => 'Senior Engineer']);


        //Programmer
        Role::create(['name' => 'Programmer']);


        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdmin = User::where('name', 'Super Admin')->first();
        $superAdmin->assignRole($superAdminRole);


//        $director = User::where('name', ['Director'])->first();
//        $director->assignRole($directorRole);
//
//        $generalManager = User::where('name', ['General Manager'])->first();
//        $generalManager->assignRole($generalManagerRole);
//
//        $financeManager = User::where('name', ['FA & Tax Manager'])->first();
//        $financeManager->assignRole($financeManagerRole);
//
//
//        $operationalManager = User::where('name', ['Operational Manager'])->first();
//        $operationalManager->assignRole($operationalManagerRole);
//
//
//        $branchManager = User::where('name', ['Branch Manager'])->first();
//        $branchManager->assignRole($branchManagerRole);
    }
}
