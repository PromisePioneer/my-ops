<?php

namespace App\Support\Inventory\StockManagement\DraftStock\Service;

namespace App\Support\Inventory\DraftStock\Service;

use AllowDynamicProperties;
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
            $totalDraftStockQty = DraftStockRepository::draftStockQtySumByItemId($query->id);

            return [
                'id' => $query->id,
                'name' => $query->name,
                'qty' => $totalDraftStockQty,
            ];
        })->filter(function ($item) {
            $totalDraftStockQty = DraftStockRepository::draftStockQtySumByItemId($item['id']);
            return $totalDraftStockQty > 0;
        });

        $itemData->setCollection($data);
        return $itemData;
    }
}
