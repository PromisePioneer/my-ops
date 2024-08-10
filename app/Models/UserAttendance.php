<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $absen_location_id
 * @property string $date
 * @property int $user_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserJobInformation|null $jobInformation
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserAttendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAttendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAttendance query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAttendance whereAbsenLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAttendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAttendance whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAttendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAttendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAttendance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAttendance whereUserId($value)
 *
 * @mixin \Eloquent
 */
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
