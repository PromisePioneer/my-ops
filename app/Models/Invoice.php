<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

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
