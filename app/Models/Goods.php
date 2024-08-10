<?php

namespace App\Models;

use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Branch $branch
 *
 * @method static Builder|Goods newModelQuery()
 * @method static Builder|Goods newQuery()
 * @method static Builder|Goods query()
 * @method static Builder|Goods whereAccountId($value)
 * @method static Builder|Goods whereBranchId($value)
 * @method static Builder|Goods whereConfirmationStatus($value)
 * @method static Builder|Goods whereCreatedAt($value)
 * @method static Builder|Goods whereCreatedBy($value)
 * @method static Builder|Goods whereFile($value)
 * @method static Builder|Goods whereId($value)
 * @method static Builder|Goods whereName($value)
 * @method static Builder|Goods whereQty($value)
 * @method static Builder|Goods whereSerialNumber($value)
 * @method static Builder|Goods whereTotalPrice($value)
 * @method static Builder|Goods whereType($value)
 * @method static Builder|Goods whereUnitPrice($value)
 * @method static Builder|Goods whereUnitTypeId($value)
 * @method static Builder|Goods whereUpdatedAt($value)
 *
 * @mixin Eloquent
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
