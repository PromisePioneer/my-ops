<?php

namespace App\Service\Master\General\Branch;

use AllowDynamicProperties;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class BranchService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->branchRepository = new BranchRepository();
    }

    public function data(): LengthAwarePaginator
    {
        $branch = $this->branchRepository->mainQuery()->paginate(self::$perPage);
        return self::formattedData($branch);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $branch = Branch::search($search)->query(function () {
            $this->branchRepository->mainQuery();
        })->paginate(self::$perPage);
        return self::formattedData($branch);
    }


    public function formattedData(LengthAwarePaginator $branch): LengthAwarePaginator
    {
        $data = $branch->getCollection()->map(function ($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'code' => $branch->code,
                'parent_id' => $branch->parent_id,
                'parent_name' => $branch->parent?->name ?? '',
                'address' => $branch->address,
                'children' => $branch->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'address' => $child->address,
                    ];
                }),
            ];
        });
        $branch->setCollection($data);
        return $branch;
    }


    public function getMainBranches(Request $request)
    {
        $search = $request->input('search');
        $branches = $this->branchRepository->getMainBranches($search);
        return $branches->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });
    }


    public function selectedBranch(?int $branchId): ?array
    {
        $branch = $this->branchRepository->selectedBranch($branchId);


        return [
            'id' => $branch?->id,
            'name' => $branch?->name
        ];
    }
}
