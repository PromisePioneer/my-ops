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
 * @property int $branch_id
 * @property string $version
 * @property string $ip_address
 * @property string $port
 * @property string $key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Branch $branch
 *
 * @method static Builder|FpDevice newModelQuery()
 * @method static Builder|FpDevice newQuery()
 * @method static Builder|FpDevice query()
 * @method static Builder|FpDevice whereBranchId($value)
 * @method static Builder|FpDevice whereCreatedAt($value)
 * @method static Builder|FpDevice whereId($value)
 * @method static Builder|FpDevice whereIpAddress($value)
 * @method static Builder|FpDevice whereKey($value)
 * @method static Builder|FpDevice wherePort($value)
 * @method static Builder|FpDevice whereUpdatedAt($value)
 * @method static Builder|FpDevice whereVersion($value)
 *
 * @mixin Eloquent
 */
class FpDevice extends Model
{
    protected $table = 'fp_devices';

    protected $fillable = [
        'branch_id',
        'name',
        'serial_number',
        'online'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getDataWithPagination(int $perPage): LengthAwarePaginator
    {
        return self::with('branch')->paginate($perPage);
    }

    public function searchData(Request $request): Collection|array
    {
        $search = $request->input('search');

        return self::with('branch')
            ->where('ip_address', 'like', '%'.$search.'%')
            ->where('name', 'like', '%'.$search.'%')
            ->where('serial_number', 'like', '%'.$search.'%')
            ->get();
    }
}
