<?php

namespace App\Models;

use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $branch_id
 * @property int $contact_id
 * @property string $fab_number
 * @property string $subscription_status
 * @property string $date
 * @property string $billing_address
 * @property string|null $installation_address
 * @property string $zip_code
 * @property string $file
 * @property int $status_confirmation
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Branch|null $branch
 * @property-read Contact $contact
 * @property-read User $user
 *
 * @method static Builder|Fab newModelQuery()
 * @method static Builder|Fab newQuery()
 * @method static Builder|Fab query()
 * @method static Builder|Fab whereBillingAddress($value)
 * @method static Builder|Fab whereBranchId($value)
 * @method static Builder|Fab whereContactId($value)
 * @method static Builder|Fab whereCreatedAt($value)
 * @method static Builder|Fab whereCreatedBy($value)
 * @method static Builder|Fab whereDate($value)
 * @method static Builder|Fab whereFabNumber($value)
 * @method static Builder|Fab whereFile($value)
 * @method static Builder|Fab whereId($value)
 * @method static Builder|Fab whereInstallationAddress($value)
 * @method static Builder|Fab whereStatusConfirmation($value)
 * @method static Builder|Fab whereSubscriptionStatus($value)
 * @method static Builder|Fab whereUpdatedAt($value)
 * @method static Builder|Fab whereZipCode($value)
 *
 * @mixin Eloquent
 */
class Fab extends Model
{
    protected $table = 'fab';

    protected $fillable = [
        'branch_id',
        'contact_id',
        'fab_number',
        'subscription_status',
        'date',
        'billing_address',
        'installation_address',
        'zip_code',
        'status_bast',
        'status_invoice',
        'file',
        'created_by',
    ];

    protected $with = [
        'contact',
        'branch',
        'user',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getDataBasedOnUserBranch(Request $request, int $perPage): LengthAwarePaginator
    {
        return self::with('branch', 'contact', 'user')
            ->where('branch_id', $request->user()->branch_id)
            ->paginate($perPage);
    }

    //eloquent

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function filterDataBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::where('branch_id', $branchId)->paginate($perPage);
    }
}
