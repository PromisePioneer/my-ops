<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoleHierarchy extends Model
{
    protected $table = 'role_hierarchy';
    protected $fillable = [
        'name',
        'role_id',
        'parent_id'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(RoleHierarchy::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(RoleHierarchy::class, 'parent_id');
    }
}
