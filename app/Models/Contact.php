<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'branch_id',
        'full_name',
        'company_name',
        'email',
        'phone_number',
        'identity_type',
        'identity_number',
        'fax',
        'npwp',
        'complete_address',
        'other_info',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    //eloquent
    public function getDataWithPaginationBasedOnUserBranch(int|null $branchId, int $perPage): LengthAwarePaginator
    {
        return self::where('branch_id', $branchId)->paginate($perPage);
    }

    public function searchDataBasedOnUserBranch(Request $request): Collection
    {
        $search = $request->input('search');

        return self::where('full_name', 'like', '%'.$search.'%')
            ->where('branch_id', $request->user()->branch_id)
            ->orWhere('company_name', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%')
            ->orWhere('phone_number', 'like', '%'.$search.'%')
            ->orWhere('identity_type', 'like', '%'.$search.'%')
            ->orWhere('identity_number', 'like', '%'.$search.'%')
            ->orWhere('fax', 'like', '%'.$search.'%')
            ->orWhere('npwp', 'like', '%'.$search.'%')
            ->orWhere('complete_address', 'like', '%'.$search.'%')
            ->orWhere('other_info', 'like', '%'.$search.'%')
            ->limit(25)
            ->get();
    }

    public function filterDataBasedOnUserBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        return self::where('branch_id', $branchId)->paginate($perPage);
    }

    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('full_name', 'asc');
        if ($search !== '') {
            $query->where('full_name', 'like', '%'.$request->search.'%')
                ->where('full_name', 'like', '%'.$request->search.'%');
        }
        $contact = $query->get(['id', 'full_name']);

        return $contact->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->full_name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $contactId): array
    {
        $contact = self::where('id', $contactId)->first();

        return [
            'id' => $contact->id,
            'name' => $contact->full_name,
            'company_name' => $contact->company_name,
        ];
    }
}
