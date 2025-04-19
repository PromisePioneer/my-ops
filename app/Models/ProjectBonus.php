<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectBonus extends Model
{
    use HasFactory;

    protected $table = 'project_bonus';
    protected $fillable = [
        'date_active',
        'customer_name',
        'bast',
        'baa',
        'work_description',
    ];


    public function userHasProjectBonus(): HasMany
    {
        return $this->hasMany(UserHasProjectBonus::class, 'project_bonus_id');
    }
}
