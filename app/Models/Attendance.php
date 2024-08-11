<?php

namespace App\Models;

use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $absen_location_id
 * @property string $date
 * @property int $user_id
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read JobInformation|null $jobInformation
 * @property-read User $user
 *
 * @method static Builder|Attendance newModelQuery()
 * @method static Builder|Attendance newQuery()
 * @method static Builder|Attendance query()
 * @method static Builder|Attendance whereAbsenLocationId($value)
 * @method static Builder|Attendance whereCreatedAt($value)
 * @method static Builder|Attendance whereDate($value)
 * @method static Builder|Attendance whereId($value)
 * @method static Builder|Attendance whereStatus($value)
 * @method static Builder|Attendance whereUpdatedAt($value)
 * @method static Builder|Attendance whereUserId($value)
 *
 * @mixin Eloquent
 */
class Attendance extends Model
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
        return $this->hasOne(JobInformation::class, 'user_id');
    }

    //eloquent
    public function getDataWithPaginationBasedOnUser(int $userId, int $perPage): LengthAwarePaginator
    {
        return self::where('user_id', $userId)->paginate($perPage);
    }
}
