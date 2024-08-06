<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class LeaveAndPermission extends Model
{
    use HasFactory;

    protected $table = 'leaves_and_permissions';
    protected $fillable = [
        'start_date',
        'end_date',
        'user_id',
        'reason',
        'leaves_status',
        'confirmation_status',
        'sick_letter',
        'approved_reason',
        'rejected_reason',
        'acc_by',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function accBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acc_by');
    }


    public function getDataWithPagination(int $userId, int $perPage): LengthAwarePaginator
    {
        return self::where('user_id', $userId)->paginate($perPage);
    }

    public function searchData(Request $request)
    {
        $search = $request->input('search');

        return self::where('user_id', $request->user()->id)
            ->where('date', 'like', '%' . $search . '%')
            ->orWhere('reason', 'like', '%' . $search . '%')
            ->orWhere('leaves_status', 'like', '%' . $search . '%')
            ->orWhere('confirmation_status', 'like', '%' . $search . '%')
            ->get();
    }
}
