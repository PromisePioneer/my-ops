<?php

namespace App\Support\Inventory\StockManagement\DraftStock\Service;

namespace App\Support\Inventory\DraftStock\Service;

use AllowDynamicProperties;
use App\Models\DraftStock;
use App\Support\Inventory\StockManagement\DraftStock\Repository\DraftStockRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class DraftStockService
{


    private static int $perPage = 10;


    public function __construct()
    {
        $this->draftStockRepostitory = new DraftStockRepository();
    }


    public function getQty(): ?int
    {
        return $this->draftStockRepostitory->getQty();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $items = $this->draftStockRepostitory->getDraftStockQuery()->paginate(self::$perPage);
        return self::formattedData($request, $items);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->draftStockRepostitory->getDraftStockQuery();
        if (!empty($search)) {
            $query = $this->draftStockRepostitory->searchQuery($query, $search);
        }

        $items = $query->paginate(self::$perPage);
        return self::formattedData($request, $items);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->draftStockRepostitory->getDraftStockQuery();
        $filter = DraftStockQueryFilter::apply($query, $request)->paginate(self::$perPage);
        return self::formattedData($request, $filter);
    }


    private static function formattedData(Request $request, LengthAwarePaginator $itemData): LengthAwarePaginator
    {
        $data = $itemData->getCollection()->map(function ($query) use ($request) {
            $draftStock = DraftStock::leftJoin('transactions', 'draft_stocks.transaction_id', '=', 'transactions.id')
                ->leftJoin('item_collections', 'transactions.item_id', '=', 'item_collections.id')
                ->leftJoin('initial_inventory_balance', 'draft_stocks.initial_balance_inventory_id', '=', 'initial_inventory_balance.id')
                ->where('transactions.item_id', $query->id)
                ->select('draft_stocks.*')->sum('draft_stocks.qty');

            return [
                'id' => $query->id,
                'name' => $query->name,
                'qty' => $draftStock,
            ];
        });

        $itemData->setCollection($data);
        return $itemData;
    }
}
