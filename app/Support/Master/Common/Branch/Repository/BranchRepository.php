<?php

namespace App\Support\Master\Common\Branch\Repository;

use App\Models\Master\Common\Branch;
use App\Support\Master\Common\Branch\Interface\BranchRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BranchRepository implements BranchRepositoryInterface
{
    public function handle(): Builder
    {
        return Branch::with('children')
            ->whereNull('parent_id')
            ->orderBy('code');
    }

    public function getAllBranches(string $search): Collection
    {
        return Branch::search($search)->query(function ($query) {
            $query->orderBy('code');
        })->get();
    }

    public function getMainBranches(Request $request): Collection
    {
        $search = $request->input('search');
        return Branch::search($search)->query(function ($query) {
            $query->whereNull('parent_id');
        })->get();
    }


    public function getSubBranches(Request $request, $mainBranchId): Collection
    {
        $search = $request->input('search');

        return Branch::search($search)->query(function ($query) use ($mainBranchId) {
            $query->where('parent_id', $mainBranchId);
        })->get();

    }


    public function getSelectedBranch(?int $branchId): ?Branch
    {
        return Branch::where('id', $branchId)->first();
    }
}
