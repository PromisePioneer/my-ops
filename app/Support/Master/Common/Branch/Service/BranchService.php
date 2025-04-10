<?php

namespace App\Support\Master\Common\Branch\Service;

use AllowDynamicProperties;
use App\Models\Master\Common\Branch;
use App\Support\Master\Common\Branch\Interface\BranchServiceInterface;
use App\Support\Master\Common\Branch\Repository\BranchRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class BranchService implements BranchServiceInterface
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->branchRepository = new BranchRepository();
    }

    public function data(): LengthAwarePaginator
    {
        $branch = $this->branchRepository->handle()->paginate(self::$perPage);
        return self::formattedData($branch);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $branch = Branch::search($search)->query(function () {
            $this->branchRepository->handle();
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
        $branches = $this->branchRepository->getMainBranches($request);
        return $branches->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });
    }


    public function getSubBranches(Request $request, $mainBranchId)
    {
        $branches = $this->branchRepository->getSubBranches($request, $mainBranchId);
        return $branches->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });
    }



    public function selectedBranch(?int $branchId): ?array
    {
        $branch = $this->branchRepository->getSelectedBranch($branchId);

        return [
            'id' => $branch?->id,
            'code' => $branch?->code,
            'name' => $branch?->name
        ];
    }
}
