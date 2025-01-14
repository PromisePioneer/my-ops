<?php

namespace App\Service\ItemTransaction;

use App\Models\GoodsTransaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PoService
{

    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        return GoodsTransaction::with('po', 'item', 'item.unitType', 'warehouse')
            ->where('from_po', 1)
            ->paginate(self::$perPage);

//        return self::formattedData($itemTransactions);
    }


//    private static function formattedData(LengthAwarePaginator $itemTransactions)
//    {
//        $data = $itemTransactions->getCollection()->map(function ($itemTransactions) {
//            return [
//                ''
//            ]
//        });
//    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');

        return GoodsTransaction::with('po', 'item', 'item.unitType', 'warehouse')
            ->where('from_po', 1)
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->whereHas('po', function ($query) use ($search) {
                        $query->where('po_number', 'like', '%' . $search . '%');
                    })->orWhereHas('item', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('warehouse', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
                }
            })
            ->paginate(self::$perPage);
    }
}
