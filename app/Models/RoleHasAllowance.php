<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleHasAllowance extends Model
{
    use HasFactory;

    protected $table = 'role_has_allowance';
    protected $fillable = [
        'role_id',
        'allowance_id',
    ];


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function payrollAllowance(): BelongsTo
    {
        return $this->belongsTo(PayrollAllowance::class, 'allowance_id');
    }


}
