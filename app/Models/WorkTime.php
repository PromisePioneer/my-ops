<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class WorkTime extends Model
{
    use HasFactory;

    protected $table = 'work_time';

    protected $fillable = [
        'name',
        'clock_in',
        'clock_out',
        'time_to_checkin',
        'end_time_to_checkin',
        'time_to_checkout',
        'end_time_to_checkout',
    ];


    public function userWorktime(): HasMany
    {
        return $this->hasMany(UserWorkTime::class, 'work_time_id', 'id');
    }

    public function getDataWithPagination(?int $branchId, int $perPage)
    {
        return self::where('branch_id', $branchId)->paginate($perPage);
    }

    public function searchDataWithPagination(Request $request, int $perPage)
    {
        $search = $request->input('search');

        return self::where('branch_id', $request->user()->branch_id)
            ->orWhere('name', 'like', '%'.$search.'%')
            ->paginate($perPage);
    }
}
