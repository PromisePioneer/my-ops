<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * 
 *
 * @property int $id
 * @property int $branch_id
 * @property string $version
 * @property string $ip_address
 * @property string $port
 * @property string $key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch $branch
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AttendanceMachineInformation whereVersion($value)
 * @mixin \Eloquent
 */
class AttendanceMachineInformation extends Model
{
    protected $table = 'attendance_machine_information';

    protected $fillable = [
        'branch_id',
        'version',
        'ip_address',
        'port',
        'key',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getDataWithPagination(int $perPage): LengthAwarePaginator
    {
        return self::with('branch')->paginate($perPage);
    }

    public function searchData(Request $request)
    {
        $search = $request->input('search');

        return self::with('branch')
            ->where('ip_address', 'like', '%'.$search.'%')
            ->where('version', 'like', '%'.$search.'%')
            ->where('ip_address', 'like', '%'.$search.'%')
            ->where('key', 'like', '%'.$search.'%')
            ->get();
    }
}
