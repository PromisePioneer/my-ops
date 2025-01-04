<?php

namespace App\Service;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchService
{

    private static int $perPage = 10;

    public function query(): Builder
    {
        return Branch::with('children')->whereNull('parent_id');
    }

    public function data(): LengthAwarePaginator
    {
        $branch = $this->query()->paginate(self::$perPage);
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
                'children' => $branch->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                    ];
                }),
            ];
        });
        $branch->setCollection($data);
        return $branch;
    }
}
