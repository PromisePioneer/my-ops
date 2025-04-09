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
        $customerPaymentSpv = Role::where('name', 'Customer Payment Supervisor')->first();
        $financeAndAccountingSpv = Role::where('name', 'Finance & Accounting Supervisor')->first();
        $faSeniorStaff = Role::where('name', 'FA Senior Staff')->first();


        $legal = Role::where('name', 'Legal & Corporate Commissioner')->first();
        $hr = Role::where('name', 'HR & Operational Staff')->first();
        $backbone = Role::where('name', 'Backbone Team Supervisor')->first();
        $stockerSpv = Role::where('name', 'Stocker Supervisor')->first();
        $vendorSpv = Role::where('name', 'Project Controller & Vendor Supervisor')->first();
        $qcSpv = Role::where('name', 'Quality Controller Supervisor')->first();
        $afterSaleCS = Role::where('name', 'After Sales Customer Service')->first();
        $programmer = Role::where('name', 'Programmer')->first();
        $graphicDesigner = Role::where('name', 'Graphic Designer')->first();


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


        $this->accounting($taxAdminSpv, $financeManagerHierarchy, $billingAdminSpv, $customerPaymentSpv, $financeAndAccountingSpv, $faSeniorStaff);
        $this->operational($operationalManagerHierarchy, $hr, $legal, $backbone, $stockerSpv, $vendorSpv, $qcSpv, $afterSaleCS, $programmer, $graphicDesigner);

    }


    public function operational($operationalManagerHierarchy, $hr, $legal, $backbone, $stockerSpv, $vendorSpv, $qcSpv, $afterSaleCS, $programmer, $graphicDesigner)
    {
        // tax admin spv
        RoleHierarchy::create([
            'role_id' => $hr->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);


        // billing admin spv
        RoleHierarchy::create([
            'role_id' => $hr->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $legal->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $backbone->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);


        RoleHierarchy::create([
            'role_id' => $stockerSpv->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);


        RoleHierarchy::create([
            'role_id' => $vendorSpv->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);


        RoleHierarchy::create([
            'role_id' => $qcSpv->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $afterSaleCS->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $qcSpv->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $stockerSpv->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $vendorSpv->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $programmer->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $programmer->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);

        RoleHierarchy::create([
            'role_id' => $graphicDesigner->id,
            'parent_id' => $operationalManagerHierarchy->id,
        ]);

    }


    public function accounting($taxAdminSpv, $financeManagerHierarchy, $billingAdminSpv, $customerPaymentSpv, $financeAndAccountingSpv, $faSeniorStaff): void
    {
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
