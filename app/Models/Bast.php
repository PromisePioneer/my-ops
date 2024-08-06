<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class Bast extends Model
{
    protected $table = 'bast';

    protected $fillable = [
        'branch_id',
        'fab_id',
        'bast_number',
        'contact_id',
        'date',
        'first_party_identity_name',
        'first_party_position',
        'objective',
        'file',
        'status',
        'created_by',
    ];

    protected $with = [
        'contact',
        'fab',
        'user',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function fab(): BelongsTo
    {
        return $this->belongsTo(Fab::class, 'fab_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getDataWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        return self::with('contact', 'user')
            ->where('branch_id', $request->user()->branch_id)
            ->paginate($perPage);
    }

    public function searchDataBasedOnUserBranch(Request $request)
    {
        $search = $request->input('search');

        return self::where('bast_number', 'like', '%'.$search.'%')
            ->where('branch_id', $request->user()->branch_id)
            ->orWhereHas('contact', function ($query) use ($search) {
                $query->where('full_name', 'like', '%'.$search.'%');
                $query->orWhere('company_name', 'like', '%'.$search.'%');
            })
            ->orWhere('date', 'like', '%'.$search.'%')
            ->orWhere('first_party_identity_name', 'like', '%'.$search.'%')
            ->orWhere('first_party_position', 'like', '%'.$search.'%')
            ->orWhere('objective', 'like', '%'.$search.'%')
            ->orWhere('file', 'like', '%'.$search.'%')
            ->orWhere('status', 'like', '%'.$search.'%')
            ->get();
    }

    public function filterDataBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::with('contact', 'user')->where('branch_id', $branchId)->paginate($perPage);
    }
}
