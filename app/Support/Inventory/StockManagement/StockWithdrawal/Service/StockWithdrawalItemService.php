<?php

namespace App\Support\Inventory\StockManagement\StockWithdrawal\Service;

use AllowDynamicProperties;
use App\Support\Inventory\StockManagement\StockWithdrawal\Repository\StockWithdrawalItemRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class StockWithdrawalItemService
{

    private static int $perPage = 10;

    public function __construct()
    {
        $this->stockWithdrawalItemRepository = new StockWithdrawalItemRepository();
    }

    public function getCarriedStockCount()
    {
        return $this->stockWithdrawalItemRepository->carriedStockCount();
    }


    public function data(): LengthAwarePaginator
    {
        $carriedStocks = $this->stockWithdrawalItemRepository->getCarriedStock()->paginate(self::$perPage);
        return self::formattedData($carriedStocks);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->stockWithdrawalItemRepository->getCarriedStock();
        if (!empty($search)) {
            $query = $this->stockWithdrawalItemRepository->search($query, $search);
        }
        return self::formattedData($query->paginate(self::$perPage));
    }

    public function filter(Request $request): LengthAwarePaginator
    {
        $carriedStocks = $this->stockWithdrawalItemRepository->getCarriedStock();
        $filterQuery = StockWithdrawalItemQueryFilter::apply($carriedStocks, $request)->paginate(self::$perPage);
        return self::formattedData($filterQuery);
    }


    public function formattedData(LengthAwarePaginator $carriedStocks): LengthAwarePaginator
    {
        $data = $carriedStocks->getCollection()->map(function ($query) {
            return [
                'id' => $query->id,
                'pic' => $query->stockWithdrawal->pic->name,
                'stocker' => $query->stockWithdrawal->stocker->name,
                'branch_name' => $query->stockWithdrawal->branch->name,
                'stock_withdrawal_id' => $query->stock_withdrawal_id,
                'item_name' => $query->stock->item->name,
                'code' => $query->code,
                'status' => $query->status,
                'qty' => $query->qty,
            ];
        });

        $carriedStocks->setCollection($data);
        return $carriedStocks;
    }

}
