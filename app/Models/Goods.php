<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Goods extends Model
{
    protected $table = 'goods';

    protected $fillable = [
        'account_id',
        'branch_id',
        'unit_type_id',
        'serial_number',
        'qty',
        'name',
        'unit_price',
        'total_price',
        'file',
        'confirmation_status',
        'type',
        'created_by',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getDataWithPaginationBasedOnUserBranch(Request $request, int $perPage): LengthAwarePaginator
    {
        return self::where('branch_id', $request->user()->branch_id)->paginate($perPage);
    }

    public function searchDataBasedOnUserBranch(Request $request): Collection
    {
        $search = $request->input('search');

        return self::where('serial_number', 'like', '%'.$search.'%')
            ->orWhere('name', 'like', '%'.$search.'%')
            ->get();
    }

    public function filterDataBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::where('branch_id', $branchId)->paginate($perPage);
    }
}
