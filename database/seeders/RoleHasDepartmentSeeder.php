<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\RoleHasDepartment;
use Illuminate\Database\Seeder;

class RoleHasDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private Role $roles;
    private Department $departments;

    public function __construct()
    {
        $this->roles = new Role();
        $this->departments = new Department();
    }


    public function run(): void
    {
        // managerial
        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Main Commissioner')->first()->id,
            'department_id' => $this->departments->where('name', 'Managerial')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Director')->first()->id,
            'department_id' => $this->departments->where('name', 'Managerial')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'General Manager')->first()->id,
            'department_id' => $this->departments->where('name', 'Managerial')->first()->id,
        ]);


        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'FA & Tax Manager')->first()->id,
            'department_id' => $this->departments->where('name', 'Managerial')->first()->id,
        ]);


        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Operational Manager')->first()->id,
            'department_id' => $this->departments->where('name', 'Managerial')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Branch Manager')->first()->id,
            'department_id' => $this->departments->where('name', 'Managerial')->first()->id,
        ]);


        //Finance
        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Tax Admin Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'Finance')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Billing Admin Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'Finance')->first()->id,
        ]);


        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Inventory Controller Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'Finance')->first()->id,
        ]);


        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Customer Payment Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'Finance')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Finance & Accounting Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'Finance')->first()->id,
        ]);


        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'FA Senior Staff')->first()->id,
            'department_id' => $this->departments->where('name', 'Finance')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Finance & Accounting Staff')->first()->id,
            'department_id' => $this->departments->where('name', 'Finance')->first()->id,
        ]);


        //operational
        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Legal & Corporate Commissioner')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'HR & Operational Staff')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Backbone Team Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);


        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Stocker Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);


        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Stocker Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Project Controller & Vendor Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);


        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Quality Controller Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);


        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Trainer & Quality Control Staff')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Quality Control Staff')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'After Sales Customer Service')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Stocker Staff')->first()->id,
            'department_id' => $this->departments->where('name', 'Operational')->first()->id,
        ]);


        //warehouse
        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Welding Senior Engineer')->first()->id,
            'department_id' => $this->departments->where('name', 'Warehouse')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Electrical Senior Engineer')->first()->id,
            'department_id' => $this->departments->where('name', 'Warehouse')->first()->id,
        ]);
        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Warehouse Security')->first()->id,
            'department_id' => $this->departments->where('name', 'Warehouse')->first()->id,
        ]);


        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Warehouse Stocker Staff')->first()->id,
            'department_id' => $this->departments->where('name', 'Warehouse')->first()->id,
        ]);


        //NOC
        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'NOC Supervisor')->first()->id,
            'department_id' => $this->departments->where('name', 'NOC')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'NOC Staff')->first()->id,
            'department_id' => $this->departments->where('name', 'NOC')->first()->id,
        ]);

        //KU
        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'KU Head Engineer')->first()->id,
            'department_id' => $this->departments->where('name', 'KU')->first()->id,
        ]);
        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'KU Senior Engineer')->first()->id,
            'department_id' => $this->departments->where('name', 'KU')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'KU Engineer')->first()->id,
            'department_id' => $this->departments->where('name', 'KU')->first()->id,
        ]);

        //area
        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Head Engineer')->first()->id,
            'department_id' => $this->departments->where('name', 'Area')->first()->id,
        ]);

        RoleHasDepartment::create([
            'role_id' => $this->roles->where('name', 'Senior Engineer')->first()->id,
            'department_id' => $this->departments->where('name', 'Area')->first()->id,
        ]);
    }
}
