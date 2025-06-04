<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleDefaultWorkTime extends Model
{
    protected $table = 'role_default_work_time';
    protected $fillable = [
        'role_id',
        'work_time_id'
    ];


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }


    public function workTime(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'work_time_id');
    }
}
