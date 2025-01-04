<?php

namespace App\Service;

use App\Models\CentralWarehouseItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CentralWarehouseItemService
{

    private static int $perPage = 10;


    public function query(): Builder
    {
        return CentralWarehouseItem::with('item', 'warehouse', 'po', 'unitType')->withCount('centralWarehouseStock');
    }

    public function data(): LengthAwarePaginator
    {
        return $this->query()->paginate(self::$perPage);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        return $this->query()->when(!empty($search), function (Builder $query) use ($search) {
            $query->whereHas('item', function (Builder $query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('warehouse', function (Builder $query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        })->paginate(self::$perPage);
    }
}
