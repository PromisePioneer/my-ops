<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHasProjectBonus extends Model
{
    use HasFactory;

    protected $table = 'user_has_project_bonus';
    protected $fillable = [
        'project_bonus_id',
        'user_id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projectBonus(): BelongsTo
    {
        return $this->belongsTo(ProjectBonus::class);
    }
}
