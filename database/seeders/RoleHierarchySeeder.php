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


        $directorHierarchy = RoleHierarchy::create([
            'role_id' => $director->id,
        ]);


        $this->accounting($financeManagerRole, $directorHierarchy);
        $this->operational($operationalManagerRole, $directorHierarchy);
        $this->branchManagerRoleHierarchy($branchManagerRole, $directorHierarchy);

    }


    public function branchManagerRoleHierarchy($branchManagerRole, $directorHierarchy): void
    {
        $branchManagerRoleHierarchy = RoleHierarchy::create([
            'role_id' => $branchManagerRole->id,
            'parent_id' => $directorHierarchy->id,
        ]);


        $accountingStaff = Role::where('name', 'Finance & Accounting Staff')->first();
        $customerService = Role::where('name', 'Customer Service Staff')->first();
        $stocker = Role::where('name', 'Stocker Staff')->first();
        $headEngineer = Role::where('name', 'Head Engineer')->first();
        $seniorEngineer = Role::where('name', 'Senior Engineer')->first();
        $engineer = Role::where('name', 'Engineer')->first();
        $support = Role::where('name', 'Support')->first();

        $branch = [
            $accountingStaff->id,
            $customerService->id,
            $stocker->id,
            $headEngineer->id,
            $seniorEngineer->id,
            $engineer->id,
            $support->id,
        ];


        foreach ($branch as $key => $value) {
            RoleHierarchy::create([
                'role_id' => $value,
                'parent_id' => $branchManagerRoleHierarchy->id,
            ]);
        }

        $headEngineerHierarchy = RoleHierarchy::where('parent_id', $branchManagerRoleHierarchy->id)
            ->where('role_id', $headEngineer->id)
            ->first();

        $this->headEngineerHierarchy($headEngineerHierarchy);
    }


    public function operational($operationalManagerRole, $directorHierarchy): void
    {
        $operationalManagerHierarchy = RoleHierarchy::create([
            'role_id' => $operationalManagerRole->id,
            'parent_id' => $directorHierarchy->id,
        ]);

        $legal = Role::where('name', 'Legal & Corporate Commissioner')->first();
        $hr = Role::where('name', 'HR & Operational Staff')->first();
        $backboneSpv = Role::where('name', 'Backbone Team Supervisor')->first();
        $stockerSpv = Role::where('name', 'Stocker Supervisor')->first();
        $vendorSpv = Role::where('name', 'Project Controller & Vendor Supervisor')->first();
        $qcSpv = Role::where('name', 'Quality Controller Supervisor')->first();
        $afterSaleCS = Role::where('name', 'After Sales Customer Service')->first();
        $programmer = Role::where('name', 'Programmer')->first();
        $graphicDesigner = Role::where('name', 'Graphic Designer & Socmed Admin')->first();
        $headEngineer = Role::where('name', 'Head Engineer')->first();
        $warehouseStockerStaff = Role::where('name', 'Warehouse Stocker Staff')->first();
        $warehouseSecurityStaff = Role::where('name', 'Warehouse Security')->first();
        $custServiceLeader = Role::where('name', 'Customer Service Leader')->first();
        $mechanicSeniorStaff = Role::where('name', 'Mechanic Senior Staff')->first();
        $nocSpv = Role::where('name', 'NOC Supervisor')->first();

        $operational = [
            $legal->id,
            $hr->id,
            $backboneSpv->id,
            $stockerSpv->id,
            $qcSpv->id,
            $afterSaleCS->id,
            $programmer->id,
            $graphicDesigner->id,
            $vendorSpv->id,
            $headEngineer->id,
            $warehouseStockerStaff->id,
            $warehouseSecurityStaff->id,
            $custServiceLeader->id,
            $mechanicSeniorStaff->id,
            $nocSpv->id
        ];

        foreach ($operational as $key => $value) {
            RoleHierarchy::create([
                'role_id' => $value,
                'parent_id' => $operationalManagerHierarchy->id,
            ]);
        }


        $headEngineerHierarchy = RoleHierarchy::where('parent_id', $operationalManagerHierarchy->id)
            ->where('role_id', $headEngineer->id)
            ->first();
        $nocSpvHierarchy = RoleHierarchy::where('parent_id', $operationalManagerHierarchy->id)
            ->where('role_id', $nocSpv->id)
            ->first();

        $this->headEngineerHierarchy($headEngineerHierarchy);
        $this->nocHierarchy($nocSpvHierarchy);
    }


    public function accounting($financeManagerRole, $directorHierarchy): void
    {
        $financeManagerHierarchy = RoleHierarchy::create([
            'role_id' => $financeManagerRole->id,
            'parent_id' => $directorHierarchy->id,
        ]);

        $taxAdminSpv = Role::where('name', 'Tax Admin Supervisor')->first();
        $billingAdminSpv = Role::where('name', 'Billing Admin Supervisor')->first();
        $customerPaymentSpv = Role::where('name', 'Customer Payment Supervisor')->first();
        $financeAndAccountingSpv = Role::where('name', 'Finance & Accounting Supervisor')->first();
        $faSeniorStaff = Role::where('name', 'FA Senior Staff')->first();

        $accounting = [
            $taxAdminSpv->id,
            $billingAdminSpv->id,
            $customerPaymentSpv->id,
            $financeAndAccountingSpv->id,
            $faSeniorStaff->id
        ];

        foreach ($accounting as $key => $value) {
            RoleHierarchy::create([
                'role_id' => $value,
                'parent_id' => $financeManagerHierarchy->id,
            ]);
        }
    }


    public function nocHierarchy($nocSpvHierarchy): void
    {
        $nocStaff = Role::where('name', 'NOC Staff')->first();

        $data = [
            $nocStaff->id,
        ];


        foreach ($data as $key => $value) {
            RoleHierarchy::create([
                'role_id' => $value,
                'parent_id' => $nocSpvHierarchy->id,
            ]);
        }
    }


    public function headEngineerHierarchy($headEngineerHierarchy): void
    {
        $engineerRole = Role::where('name', 'Engineer')->first();
        $seniorEngineer = Role::where('name', 'Senior Engineer')->first();

        $data = [
            $engineerRole->id,
            $seniorEngineer->id
        ];

        foreach ($data as $key => $value) {
            RoleHierarchy::create([
                'role_id' => $value,
                'parent_id' => $headEngineerHierarchy->id,
            ]);
        }
    }

}
