<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

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
