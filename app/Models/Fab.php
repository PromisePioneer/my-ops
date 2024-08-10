<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Contact $contact
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Fab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fab query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereFabNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereInstallationAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereStatusConfirmation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereSubscriptionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fab whereZipCode($value)
 *
 * @mixin \Eloquent
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    //eloquent
    public function getDataBasedOnUserBranch(Request $request, int $perPage): LengthAwarePaginator
    {
        return self::with('branch', 'contact', 'user')
            ->where('branch_id', $request->user()->branch_id)
            ->paginate($perPage);
    }

    public function searchDataBasedOnUserBranch(Request $request): Collection
    {
        return self::with('contact', 'branch')
            ->where('subscription_status', 'like', '%'.$request->input('search').'%')
            ->orWhereHas('branch', function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->input('search').'%');
            })
            ->orWhereHas('contact', function ($query) use ($request) {
                $query->where('full_name', 'like', '%'.$request->input('search').'%');
                $query->where('company_name', 'like', '%'.$request->input('search').'%');
            })->get();
    }

    public function filterDataBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::where('branch_id', $branchId)->paginate($perPage);
    }
}
