<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

class UserWorkTIme extends Model
{
    use HasFactory;


    protected $table = 'user_work_time';
    protected $fillable = [
        'user_id',
        'work_time_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function workTime(): BelongsTo
    {
        return $this->belongsTo(WorkTime::class, 'work_time_id');
    }


    public function getSelectedUserShift(int $workTimeId): array
    {
        $shift = self::with('user')->where('work_time_id', $workTimeId)->get();

        return $shift->map(function ($workTime) {
            return [
                'id' => $workTime->user->id,
                'name' => $workTime->user->name,
            ];
        })->toArray();
    }


    public function getDetailUserOnSelectedWorkTime($workTimeId, int $perPage): LengthAwarePaginator
    {
        return self::with('user', 'user.roles')
            ->where('work_time_id', $workTimeId)
            ->paginate($this->perPage);
    }
}
