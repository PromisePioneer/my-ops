<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendances extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'sn',
        'table',
        'stamp',
        'employee_id',
        'timestamp',
        'status1',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id', 'absent_id');
    }


    public function getAttendancesLog()
    {
        return self::join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
            ->whereNotNull('attendances.employee_id')
            ->whereNotNull('users.name')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->orderBy('attendances.timestamp', 'desc')
            ->select(
                'attendances.employee_id',
                'users.name as user_name',
                'users.nip as user_nip',
                'attendances.timestamp',
                'attendances.status1',
                'work_time.name as work_time',
                'attendances.sn'
            );
    }


}
