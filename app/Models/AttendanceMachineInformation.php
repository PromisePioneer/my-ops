<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

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
