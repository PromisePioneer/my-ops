<?php

namespace App\Support\User\Role\Repository;

use App\Models\Master\Common\Branch;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class RoleRepository
{
    public function getRoleWithWorkTime(): Builder
    {
        return Role::with('defaultWorkTime');
    }


    public function getBranchRoles(Branch $branch)
    {
        return Role::whereNotIn('name', [
            'Main Commissioner',
            'General Manager',
            'Inventory Controller Supervisor',
            'FA Senior Staff',
            'HR & Operational Staff',
            'Project Controller & Vendor Supervisor',
            'Quality Control Staff',
            'Graphic Designer & Socmed Admin',
            'Welding Senior Engineer',
            'Warehouse Security',
            'FA & Tax Manager',
            'Tax Admin Supervisor',
            'Customer Payment Supervisor',
            'Backbone Team Supervisor',
            'Quality Controller Supervisor',
            'After Sales Customer Service',
            'Mechanic Senior Staff',
            'Electrical Senior Engineer',
            'Warehouse Stocker Staff',
            'Customer Service Leader',
            'Operational Manager',
            'Billing Admin Supervisor',
            'Finance & Accounting Supervisor',
            'Legal & Corporate Commissioner',
            'Stocker Supervisor',
            'Trainer & Quality Control Staff',
            'Warehouse Supervisor',
            'NOC Supervisor',
            'NOC Staff',
            'Programmer',
        ]);
    }
}
