<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $start_date
 * @property string $end_date
 * @property int $user_id
 * @property string $reason
 * @property string $leaves_status
 * @property string $confirmation_status
 * @property string|null $sick_letter
 * @property string|null $confirmation_reason
 * @property int|null $acc_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $accBy
 * @property-read User $user
 *
 * @method static Builder|LeaveAndPermission newModelQuery()
 * @method static Builder|LeaveAndPermission newQuery()
 * @method static Builder|LeaveAndPermission query()
 * @method static Builder|LeaveAndPermission whereAccBy($value)
 * @method static Builder|LeaveAndPermission whereConfirmationReason($value)
 * @method static Builder|LeaveAndPermission whereConfirmationStatus($value)
 * @method static Builder|LeaveAndPermission whereCreatedAt($value)
 * @method static Builder|LeaveAndPermission whereEndDate($value)
 * @method static Builder|LeaveAndPermission whereId($value)
 * @method static Builder|LeaveAndPermission whereLeavesStatus($value)
 * @method static Builder|LeaveAndPermission whereReason($value)
 * @method static Builder|LeaveAndPermission whereSickLetter($value)
 * @method static Builder|LeaveAndPermission whereStartDate($value)
 * @method static Builder|LeaveAndPermission whereUpdatedAt($value)
 * @method static Builder|LeaveAndPermission whereUserId($value)
 *
 * @mixin Eloquent
 */
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

    // relationship
    public function accBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acc_by');
    }

    public function getData(): Builder
    {
        return self::with('accBy', 'user');
    }

    public function searchDataBasedOnUserId(Request $request): Collection|array
    {
        $search = $request->input('search');

        return self::where('user_id', $request->user()->id)
            ->where('date', 'like', '%'.$search.'%')
            ->orWhere('reason', 'like', '%'.$search.'%')
            ->orWhere('leaves_status', 'like', '%'.$search.'%')
            ->orWhere('confirmation_status', 'like', '%'.$search.'%')
            ->get();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    //eloquent
    public function searchDataWithPaginationBasedOnBranch(Request $request): Collection
    {
        $search = $request->input('search');

        return self::with('user')->whereHas('user', function ($query) use ($request, $search) {
            $query->where('branch_id', $request->user()->branch_id);
            $query->orWhere('name', 'like', '%'.$search.'%');
        })->where('start_date', 'like', '%'.$search.'%')
            ->orWhere('end_date', 'like', '%'.$search.'%')
            ->orWhere('reason', 'like', '%'.$search.'%')
            ->orWhere('leaves_status', 'like', '%'.$search.'%')
            ->orWhere('confirmation_status', 'like', '%'.$search.'%')
            ->get();
    }
}
