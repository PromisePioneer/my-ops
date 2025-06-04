<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchHasDefaultWorkTime extends Model
{
    protected $table = 'branch_has_default_work_time';
    protected $fillable = [
        'branch_id',
        'work_time_id',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function workTime(): BelongsTo
    {
        return $this->belongsTo(WorkTime::class, 'work_time_id');
    }


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }


    public function branchHasDefaultWorkTime(): HasMany
    {
        return $this->hasMany(BranchHasDefaultWorkTime::class, 'branch_id');
    }
}
