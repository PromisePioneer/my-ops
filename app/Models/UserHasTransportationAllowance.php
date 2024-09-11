<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHasTransportationAllowance extends Model
{
    use HasFactory;

    protected $table = 'user_has_transportation_allowances';
    protected $fillable = [
        'user_id',
        'date',
        'amount',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function data(): Builder
    {
        return self::with('user');
    }
}
