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
        $directorRole = Role::create(['name' => 'Direktur']);
        $financeManagerRole = Role::create(['name' => 'Manager Keuangan']);
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $kacabRole = Role::create(['name' => 'Kepala Cabang']);

        $accountantRole = Role::create(['name' => 'Accountant']);
        $kcaRole = Role::create(['name' => 'KCA']);
        $wkcaRole = Role::create(['name' => 'WKCA']);
        $technicianRole = Role::create(['name' => 'Technician']);
        $stockerRole = Role::create(['name' => 'Stocker']);
        $customerServiceRole = Role::create(['name' => 'Customer Service']);
        $NOCRole = Role::create(['name' => 'NOC']);

        $kepalaCabang = User::where('name', 'Kepala Cabang')->first();
        $kepalaCabang->assignRole($kacabRole);

        $admin = User::where('name', 'Accountant')->first();
        $admin->assignRole($accountantRole);

        $director = User::where('name', 'Direktur')->first();
        $director->assignRole($directorRole);

        $financeManager = User::where('name', 'Manager Keuangan')->first();
        $financeManager->assignRole($financeManagerRole);

        $superAdmin = User::where('name', 'Super Admin')->first();
        $superAdmin->assignRole($superAdminRole);

        $technician = User::where('name', 'Technician')->get();
        foreach ($technician as $tech) {
            $tech->assignRole($technicianRole);
        }

        $kca = User::where('name', 'KCA')->get();
        foreach ($kca as $kc) {
            $kc->assignRole($kcaRole);
        }

        $wkca = User::where('name', 'WKCA')->get();
        foreach ($wkca as $wk) {
            $wk->assignRole($wkcaRole);
        }

        $stocker = User::where('name', 'Stocker')->first();
        $stocker->assignRole($stockerRole);

        $cs = User::where('name', 'Customer Service')->get();
        foreach ($cs as $c) {
            $c->assignRole($customerServiceRole);
        }

        $noc = User::where('name', 'NOC')->get();
        foreach ($noc as $n) {
            $n->assignRole($NOCRole);
        }
    }
}
