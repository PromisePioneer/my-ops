<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{

    use Searchable;

    public function __construct()
    {
        parent::__construct();
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public function department(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'role_has_department', 'role_id', 'department_id');
    }


    public function rolePermissionAndDepartments(): Builder
    {
        return Role::with('permissions', 'department');
    }
}
