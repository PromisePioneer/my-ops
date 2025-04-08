<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleHierarchy;
use Illuminate\Database\Seeder;

class RoleHierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $director = Role::where('name', 'Director')->first();
        $financeManagerRole = Role::where('name', 'FA & Tax Manager')->first();
        $operationalManagerRole = Role::where('name', 'Operational Manager')->first();
        $branchManagerRole = Role::where('name', 'Branch Manager')->first();


        $taxAdminSpv = Role::where('name', 'Tax Admin Supervisor')->first();
        $billingAdminSpv = Role::where('name', 'Billing Admin Supervisor')->first();
        $inventoryControllerSpv = Role::where('name', 'Inventory Controller Supervisor')->first();
        $customerPaymentSpv = Role::where('name', 'Customer Payment Supervisor')->first();
        $financeAndAccountingSpv = Role::where('name', 'Finance & Accounting Supervisor')->first();
        $faSeniorStaff = Role::where('name', 'FA Senior Staff')->first();
        $faStaff = Role::where('name', 'Finance & Accounting Staff')->first();

        $directorHierarchy = RoleHierarchy::create([
            'role_id' => $director->id,
        ]);


        $financeManagerHierarchy = RoleHierarchy::create([
            'role_id' => $financeManagerRole->id,
            'parent_id' => $directorHierarchy->id,
        ]);


        $operationalManagerHierarchy = RoleHierarchy::create([
            'role_id' => $operationalManagerRole->id,
            'parent_id' => $directorHierarchy->id,
        ]);


        $branchManagerRoleHierarchy = RoleHierarchy::create([
            'role_id' => $branchManagerRole->id,
            'parent_id' => $directorHierarchy->id,
        ]);


        // tax admin spv
        RoleHierarchy::create([
            'role_id' => $taxAdminSpv->id,
            'parent_id' => $financeManagerHierarchy->id,
        ]);


        // billing admin spv
        RoleHierarchy::create([
            'role_id' => $billingAdminSpv->id,
            'parent_id' => $financeManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $customerPaymentSpv->id,
            'parent_id' => $financeManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $financeAndAccountingSpv->id,
            'parent_id' => $financeManagerHierarchy->id,
        ]);


        RoleHierarchy::create([
            'role_id' => $faSeniorStaff->id,
            'parent_id' => $financeManagerHierarchy->id,
        ]);


    }
}
