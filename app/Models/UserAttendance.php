<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserAttendance extends Model
{
    use HasFactory;

    protected $table = 'user_attendance';

    protected $fillable = [
        'absen_location_id',
        'date',
        'clock_out',
        'user_id',
        'status_if_not_present',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobInformation(): HasOne
    {
        return $this->hasOne(UserJobInformation::class, 'user_id');
    }

    //eloquent
    public function getDataWithPaginationBasedOnUser(int $userId, int $perPage): LengthAwarePaginator
    {
        return self::where('user_id', $userId)->paginate($perPage);
    }
}
