<?php

namespace App\Support\Inventory\Stock\StockWithdrawal\Service;

use AllowDynamicProperties;
use App\Models\Stock;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalByEmployee;
use App\Models\StockWithdrawalItem;
use App\Support\Inventory\Stock\StockWithdrawal\Repository\StockWithdrawalServiceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function data(Request $request): LengthAwarePaginator
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
                'employees' => $stockWithdrawal->stockWithdrawalByEmployee->map(function ($query) {
                    $query->load('user');
                    return [
                        'id' => $query->id,
                        'name' => "{$query->user->nip} {$query->user->name}"
                    ];
                }),
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
    public function store(Request $request): void
    {
        DB::transaction(function () use ($request) {
            $stockWithdrawal = StockWithdrawal::create([
                'branch_id' => $request->user()->branch_id ?? 1,
                'date' => Carbon::now()->format('Y-m-d'),
                'description' => $request->input('description'),
            ]);
            $this->stockWithdrawalEmployeeStoreAndUpdate($stockWithdrawal, $request);
            $this->stockWithdrawalItemStoreOrUpdate($stockWithdrawal, $request);
        });
    }


    public function stockWithdrawalItemStoreOrUpdate($stockWithdrawal, $request): void
    {


        foreach ($request->itemWithCodeFields as $value) {
            $test = json_decode($value, true);

            dd($test);
        }


        $test = json_decode($request->itemWithCodeFields, true);
        foreach ($request['itemWithCodeFields'] as $key => $value) {

            $stockWithCode = Stock::with('item', 'itemCatalog')->whereHas('itemCatalog', function ($query) use ($value) {
                $query->where('code', $value['code']);
            })->where('id', $value['stock_id'])->first();


            $codes = [];

            foreach ($stockWithCode->itemCatalog->get() as $item) {
                $codes [] = $item->code;
                $value['item_catalog_code'] = $item->code;
                $value['stock_withdrawal_id'] = $stockWithdrawal->id;
                $value['stock_id'] = $stockWithCode->id;
//                $value['qty'] = 1;
                StockWithdrawalItem::create($value);
            }


            dd($codes);
        }

        foreach ($request['itemWithoutCodeFields'] as $key => $value) {
            $stockWithoutCode = Stock::with('item', 'itemCatalog')->where('id', $value['stock_id'])->first();
            $value['stock_withdrawal_id'] = $stockWithdrawal->id;
            $value['stock_id'] = $stockWithoutCode->id;
            StockWithdrawalItem::create($value);
        }
    }


    public function stockWithdrawalEmployeeStoreAndUpdate($stockWithdrawal, $request): void
    {

        foreach ($request->user_id as $userId) {
            StockWithdrawalByEmployee::create([
                'stock_withdrawal_id' => $stockWithdrawal->id,
                'user_id' => $userId,
            ]);
        }
    }
}
