<?php

namespace App\Service\ItemTransaction;

use App\Models\ItemTransaction;
use Illuminate\Pagination\LengthAwarePaginator;
use function App\Helper\formatDate;

class IncomingItemTransactionService
{

    private static int $perPage = 10;

    public function data()
    {
        $itemTransactions = ItemTransaction::with('item', 'po', 'item.unitType', 'warehouse')
            ->where('type', 'in')->paginate(self::$perPage);

        return self::formattedData($itemTransactions);
    }

    private static function formattedData(LengthAwarePaginator $itemTransactions)
    {
        $data = $itemTransactions->getCollection()->map(function ($itemTransactions) {
            return [
                'id' => $itemTransactions->id,
                'date' => formatDate($itemTransactions->date),
                'po_number' => $itemTransactions->po->po_number,
                'item_name' => $itemTransactions->item->name,
                'warehouse_name' => $itemTransactions->warehouse->name,
                'item_unit_type' => $itemTransactions->item->unitType->name,
                'qty' => $itemTransactions->qty,
                'type' => $itemTransactions->type
            ];
        });


        $itemTransactions->setCollection($data);
        return $itemTransactions;
    }
}
