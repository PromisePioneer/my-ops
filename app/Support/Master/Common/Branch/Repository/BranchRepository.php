<?php

namespace App\Support\Master\Common\Branch\Repository;

use AllowDynamicProperties;
use App\Models\Master\Common\Branch;
use App\Support\Master\Common\Branch\Interface\BranchRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class BranchRepository implements BranchRepositoryInterface
{

    public function __construct()
    {
        $this->branch = new Branch();
    }

    public function handle(Request $request): Builder
    {
        return $this->branch->query()
            ->with(['children', 'company'])
            ->whereNull('parent_id')
            ->where('company_id', $request->session()->get('company_session'))
            ->orderBy('code');
    }

    public function getAllBranches(string $search): Collection
    {
        return $this->branch->search($search)->query(function ($query) {
            $query->orderBy('code');
        })->get();
    }

    public function getMainBranches(Request $request): Collection
    {
        $search = $request->input('search');
        return $this->branch->search($search)->query(function ($query) {
            $query->whereNull('parent_id');
        })->get();
    }


    public function getSubBranches(Request $request, int $mainBranchId): Collection
    {
        $search = $request->input('search');

        return $this->branch->search($search)->query(function ($query) use ($mainBranchId) {
            $query->where('parent_id', $mainBranchId);
        })->get();

    }


    public function getSelectedBranch(?int $branchId): ?Branch
    {
        return Branch::with('parent')->where('id', $branchId)->first();
    }


    public function getAllBranch(Request $request): Collection
    {
        $search = $request->input('search');
        $branch = $this->branch->query()
            ->with('parent', 'children')
            ->when(!empty($request->user()->branch_id), function ($query) use ($request) {
                $query->where('id', $request->user()->branch_id);
            })->whereNull('parent_id');


        if (!empty($search)) {
            $branch->whereHas('children', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhere('name', 'like', '%' . $search . '%');
        }

        return $branch->get();
    }


    public function getBranchDefaultWorkTime(): Builder
    {
        return Branch::with('defaultWorkTime')->whereNull('parent_id')->orderBy('name');
    }


    public function findById(int $id)
    {
        return $this->branch->query()->with('parent')->find($id);
    }

}
