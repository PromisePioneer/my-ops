<?php

namespace App\Service\Master\General\Branch;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BranchRepository
{
    public function mainQuery(): Builder
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

    public function getMainBranches($search): Collection
    {
        return Branch::search($search)->query(function ($query) {
            $query->whereNull('parent_id');
        })->get();
    }


    public function selectedBranch(?int $branchId)
    {
        return Branch::where('id', $branchId)->first();
    }
}
