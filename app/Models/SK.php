<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SK extends Model
{
    use HasFactory;

    protected $table = 'sk';
    protected $fillable = [
        'user_id',
        'sk_number',
        'sk_type',
        'date',
        'old_branch_id',
        'new_branch_id',
        'old_role_id',
        'new_role_id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function oldBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'old_branch_id');
    }

    public function newBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'new_branch_id');
    }

    public function oldRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'old_role_id');
    }

    public function newRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'new_role_id');
    }


    //eloquent
    public function getData(): Builder
    {
        return self::with('user', 'oldBranch', 'newBranch', 'oldRole', 'newRole');
    }
}
