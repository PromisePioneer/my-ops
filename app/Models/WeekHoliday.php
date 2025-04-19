<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeekHoliday extends Model
{
    protected $table = 'user_week_holidays';

    protected $fillable = [
        'user_id',
        'day',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
