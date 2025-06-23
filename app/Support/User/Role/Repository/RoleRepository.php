<?php

namespace App\Support\User\Role\Repository;

use AllowDynamicProperties;
use App\Models\Master\Common\Branch;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class RoleRepository
{
    public function __construct()
    {
        $this->role = new Role();
    }


    public function getRoleWithWorkTime(): Builder
    {
        return $this->role->query()
            ->with('defaultWorkTime')
            ->orderBy('name');
    }


    public function getBranchRoles(Branch $branch): Builder
    {
        return $this->role
            ->query()
            ->with(['branchRoleDefaultWorkTime' => function ($query) use ($branch) {
            $query->where('branch_id', $branch->id);
        }])->whereNotIn('name', [
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
            'Super Admin',
            'Director',
            'Vendor'
        ])->orderBy('name');
    }


    public function findById(int $id)
    {
        return $this->role->query()->find($id);
    }
}
