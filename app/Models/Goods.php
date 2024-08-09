<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * 
 *
 * @property int $id
 * @property int $account_id
 * @property int $branch_id
 * @property int $unit_type_id
 * @property string $serial_number
 * @property string $name
 * @property float $unit_price
 * @property float $total_price
 * @property int $qty
 * @property string $file
 * @property int $confirmation_status
 * @property string $type
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch $branch
 * @method static \Illuminate\Database\Eloquent\Builder|Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Goods query()
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereConfirmationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereUnitTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
