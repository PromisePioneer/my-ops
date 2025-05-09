<?php

namespace App\Support\Inventory\Stock\StockWithdrawal\Service;

use AllowDynamicProperties;
use App\Http\Requests\StockWithdrawalRequest;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalByEmployee;
use App\Models\StockWithdrawalItem;
use App\Support\Inventory\Stock\StockWithdrawal\Repository\StockWithdrawalServiceRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

#[AllowDynamicProperties] class StockWithdrawalService
{
    public function __construct()
    {
        $this->stockWithdrawalServiceRepository = new StockWithdrawalServiceRepository();
    }

    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $data = $this->stockWithdrawalServiceRepository->getStockWithDrawalQuery()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    private static function formattedData(LengthAwarePaginator $stockWithdrawal): LengthAwarePaginator
    {
        $data = $stockWithdrawal->getCollection()->map(function ($stockWithdrawal) {
            return [
                'id' => $stockWithdrawal->id,
                'branch_name' => $stockWithdrawal->branch->name,
                'date' => $stockWithdrawal->date,
                'description' => $stockWithdrawal->description,
                'pic' => $stockWithdrawal->kca?->name ?? null,
                'stocker' => $stockWithdrawal->stocker?->name ?? null,
            ];
        });

        $stockWithdrawal->setCollection($data);
        return $stockWithdrawal;
    }


    /**
     * @throws Throwable
     */
    public function store(StockWithdrawalRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $stockWithdrawal = StockWithdrawal::create([
                'branch_id' => $request->user()->branch_id ?? 1,
                'date' => Carbon::now()->format('Y-m-d'),
                'description' => $request->input('description'),
                'kca_id' => $request->user()->id,
            ]);
            $this->stockWithdrawalEmployeeStoreAndUpdate($stockWithdrawal, $request);
            $this->stockWithdrawalItemStoreOrUpdate($stockWithdrawal, $request);
        });
    }


    public function stockWithdrawalItemStoreOrUpdate($stockWithdrawal, $request): void
    {
        if ($request->has('itemWithCodeFields')) {
            foreach ($request->itemWithCodeFields as $value) {
                $itemCatalog = ItemCatalog::find($value);
                Stock::where('id', $itemCatalog->stock_id)->increment('on_hold_qty');
                ItemCatalog::create([
                    'transaction_id' => $itemCatalog->transaction_id,
                    'stock_id' => $itemCatalog->stock_id,
                    'code' => $itemCatalog->code,
                    'condition' => $itemCatalog->condition,
                    'created_by' => $itemCatalog->created_by,
                    'status' => 'Dibawa'
                ]);
                $itemCatalog->delete();
                StockWithdrawalItem::create([
                    'stock_withdrawal_id' => $stockWithdrawal->id,
                    'stock_id' => $itemCatalog->stock_id,
                    'code' => $itemCatalog->code,
                    'qty' => 1,
                ]);
            }
        }

        if ($request['itemWithoutCodeFields']) {
            foreach ($request['itemWithoutCodeFields'] as $key => $value) {
                $stockWithoutCode = Stock::with('item', 'itemCatalog')->where('id', $value['stock_id'])->first();
                $stockWithoutCode->decrement('qty', $value['qty']);
                $value['stock_withdrawal_id'] = $stockWithdrawal->id;
                $value['stock_id'] = $stockWithoutCode->id;
                StockWithdrawalItem::create($value);
            }
        }
    }


    public function stockWithdrawalEmployeeStoreAndUpdate($stockWithdrawal, $request): void
    {

        if ($request->has('user_id')) {
            foreach ($request->user_id as $userId) {
                StockWithdrawalByEmployee::create([
                    'stock_withdrawal_id' => $stockWithdrawal->id,
                    'user_id' => $userId,
                ]);
            }
        }
    }


    public function getUsers(StockWithdrawal $stockWithdrawal)
    {
        $data = StockWithdrawalByEmployee::with('user')
            ->where('stock_withdrawal_id', $stockWithdrawal->id)
            ->get();


        return $data->map(function ($query) {
            return [
                'id' => $query->id,
                'name' => $query->user->name
            ];
        });
    }

    public function getCarriedStock()
    {
        $data = StockWithdrawal::with('stockWithdrawalByEmployee', 'stockWithdrawalItem', 'stockWithdrawalItem.stock.item')->whereHas('stockWithdrawalByEmployee', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('date', Carbon::now()->format('Y-m-d'))->paginate(self::$perPage);

        $carriedStock = $data->flatMap(function ($query) {

            return $query->stockWithdrawalItem->map(function ($item) use ($query) {
                $item->load('stock.item');
                return [
                    'id' => $item->id,
                    'name' => $item->stock->item->name,
                    'code' => $item->code,
                    'stock_withdrawal_id' => $item->stock_withdrawal_id,
                    'qty' => $item->qty,
                    'status' => $query->status
                ];
            });
        });

        $data->setCollection($carriedStock);
        return $data;
    }
}
