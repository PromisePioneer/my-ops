<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function roleHierarchy(): HasMany
    {
        return $this->hasMany(RoleHierarchy::class, 'role_id');
    }


    public function defaultWorkTime(): HasOne
    {
        return $this->hasOne(RoleDefaultWorkTime::class, 'role_id');
    }
}
