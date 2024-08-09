<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

/**
 * 
 *
 * @property int $id
 * @property int $contact_id
 * @property int|null $branch_id
 * @property string $invoice_number
 * @property int $account_id
 * @property string $due_date
 * @property string|null $description
 * @property string $baa_file
 * @property string $cooperative_contract_file
 * @property float $grand_total
 * @property string $payment_status
 * @property int $status
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Contact $contact
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereBaaFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCooperativeContractFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    use Notifiable;

    protected $table = 'invoices';

    protected $fillable = [
        'fab_id',
        'contact_id',
        'branch_id',
        'invoice_number',
        'account_id',
        'due_date',
        'description',
        'baa_file',
        'cooperative_contract_file',
        'grand_total',
        'payment_status',
        'created_by',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function getDataWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        return self::with('contact', 'user')
            ->where('branch_id', $request->user()->branch_id)
            ->latest()
            ->paginate($perPage);
    }

    public function searchDataBasedOnUserBranch($request)
    {
        $search = $request->input('search');

        return self::where('invoice_number', 'like', '%'.$search.'%')
            ->where('branch_id', $request->user()->branch_id)
            ->orWhereHas('contact', function ($query) use ($search) {
                $query->where('company_name', 'like', '%'.$search.'%');
            })
            ->orWhereHas('branch', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->orWhere('due_date', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%')
            ->orWhere('grand_total', 'like', '%'.$search.'%')
            ->orWhere('created_by', 'like', '%'.$search.'%')
            ->get();
    }

    public function filterDataBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::with('contact', 'user')->where('branch_id', $branchId)->paginate($perPage);
    }
}
