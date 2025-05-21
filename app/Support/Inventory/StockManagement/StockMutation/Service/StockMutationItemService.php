<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Service;

use AllowDynamicProperties;
use App\Models\StockMutation;
use App\Support\Inventory\StockManagement\StockMutation\Repository\StockMutationItemRepository;

#[AllowDynamicProperties] class StockMutationItemService
{
    public function __construct()
    {
        $this->stockMutationItemRepository = new StockMutationItemRepository();
    }


    public function getByStockMutationId(StockMutation $stockMutation)
    {
        $stockMutationItems = $this->stockMutationItemRepository->getByStockMutationId($stockMutation->id);
        return self::formattedData($stockMutationItems);
    }

    private static function formattedData($stockMutationItems)
    {
        $data = $stockMutationItems->getCollection()->map(function ($query) {
            return [
                'id' => $query->id,
                'code' => $query->code ?? '-',
                'name' => $query->stock->item->name,
                'qty' => $query->qty
            ];
        });

        $stockMutationItems->setCollection($data);
        return $stockMutationItems;
    }
}
