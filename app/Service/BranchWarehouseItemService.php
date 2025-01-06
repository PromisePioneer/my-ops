<?php

namespace App\Service;

use App\Models\BranchWarehouseItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchWarehouseItemService
{

    private static int $perPage = 10;

    public function query(): Builder
    {
        return BranchWarehouseItem::with('po', 'item', 'branch', 'centralWarehouseStock', 'unitType');
    }

    public function data(): LengthAwarePaginator
    {
        return $this->query()->paginate(self::$perPage);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        return $this->query()->when(!empty($search), function ($query) use ($search) {
            $query->whereHas('item', function (Builder $query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
            $query->orWwhereHas('po', function (Builder $query) use ($search) {
                $query->where('po_number', 'like', '%' . $search . '%');
            });
        })->paginate(self::$perPage);
    }

}
