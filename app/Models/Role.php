<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{

    public function __construct()
    {
        parent::__construct();
    }

    public function department(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'role_has_department', 'role_id', 'department_id');
    }


    public function rolePermissionAndDepartments(): Builder
    {
        return Role::with('permissions', 'department');
    }


    public function getData(Request $request)
    {
        $search = $request->search;

        $query = self::orderBy('name')
            ->select('id', 'name');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            });
        }

        $users = $query->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip.' '.$item->name,
            ];
        })->toArray();
    }


    public function selectedRole(int $roleId): array
    {
        $role = self::where('id', $roleId)->first();
        return [
            'id' => $role->id,
            'name' => $role->name,
        ];
    }

    public function payrollAllowance(): HasMany
    {
        return $this->hasMany(PayrollAllowance::class, 'role_id');
    }
}
