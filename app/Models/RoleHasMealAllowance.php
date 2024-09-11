<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleHasMealAllowance extends Model
{
    use HasFactory;

    protected $table = 'role_has_meal_allowances';
    protected $fillable = [
        'role_id',
        'amount',
    ];


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }


    public function data(): Builder
    {
        return self::with('role');
    }
}
