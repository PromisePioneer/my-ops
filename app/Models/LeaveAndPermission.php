<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
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
        'confirmation_reason',
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

    public function searchDataBasedOnUserId(Request $request)
    {
        $search = $request->input('search');

        return self::where('user_id', $request->user()->id)
            ->where('date', 'like', '%' . $search . '%')
            ->orWhere('reason', 'like', '%' . $search . '%')
            ->orWhere('leaves_status', 'like', '%' . $search . '%')
            ->orWhere('confirmation_status', 'like', '%' . $search . '%')
            ->get();
    }


    public function getDataWithPaginationBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::with('user')->whereHas('user', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->paginate($perPage);
    }

    public function searchDataWithPaginationBasedOnBranch(Request $request): Collection
    {
        $search = $request->input('search');
        return self::with('user')->whereHas('user', function ($query) use ($request, $search) {
            $query->where('branch_id', $request->user()->branch_id);
            $query->orWhere('name', 'like', '%' . $search . '%');
        })->where('start_date', 'like', '%' . $search . '%')
            ->orWhere('end_date', 'like', '%' . $search . '%')
            ->orWhere('reason', 'like', '%' . $search . '%')
            ->orWhere('leaves_status', 'like', '%' . $search . '%')
            ->orWhere('confirmation_status', 'like', '%' . $search . '%')
            ->get();
    }
}
