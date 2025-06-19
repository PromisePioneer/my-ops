<?php

namespace App\Support\Inventory\StockManagement\StockWithdrawal\Service;

namespace App\Support\Inventory\StockWithdrawal\Service;

use AllowDynamicProperties;
use App\Http\Requests\StockWithdrawalRequest;
use App\Models\ItemCatalog;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalByEmployee;
use App\Models\StockWithdrawalItem;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\Inventory\StockManagement\StockWithdrawal\Repository\StockWithdrawalItemRepository;
use App\Support\Inventory\StockManagement\StockWithdrawal\Repository\StockWithdrawalRepository;
use App\Support\Inventory\StockManagement\StockWithdrawal\Service\StockWithdrawalQueryFilter;
use App\Support\Master\Accounting\Assets\Service\AssetService;
use Carbon\Carbon;
use Illuminate\Http\FileHelpers;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Storage;
use Throwable;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class StockWithdrawalService
{
    use FileHelpers;

    public function __construct()
    {
        $this->stockWithdrawalRepository = new StockWithdrawalRepository();
        $this->handleFileUploadService = new HandleFileUploadService();
        $this->stockWithdrawalItemRepository = new StockWithdrawalItemRepository();
        $this->assetService = new AssetService();
    }

    private static int $perPage = 10;

    public function data(Request $request): LengthAwarePaginator
    {
        $data = StockWithdrawalQueryFilter::apply(
            $this->stockWithdrawalRepository->getStockWithdrawalQuery(),
            $request
        );

        $aclFilter = StockWithdrawalACLFilter::apply($data, $request)->paginate(self::$perPage);


        return self::formattedData($aclFilter);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->stockWithdrawalRepository->getStockWithdrawalQuery();
        if (!empty($search)) {
            $query = $this->stockWithdrawalRepository->searchQuery($query, $search);
        }
        $aclFilter = StockWithdrawalACLFilter::apply($query, $request)->paginate(self::$perPage);

        return self::formattedData($aclFilter);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->stockWithdrawalRepository->getStockWithdrawalQuery();
        $query = StockWithdrawalQueryFilter::apply($query, $request);
        $aclFilter = StockWithdrawalACLFilter::apply($query, $request)->paginate(self::$perPage);
        return self::formattedData($aclFilter);
    }


    private static function formattedData(LengthAwarePaginator $stockWithdrawal): LengthAwarePaginator
    {
        $data = $stockWithdrawal->getCollection()->map(function ($stockWithdrawal) {
            return [
                'id' => $stockWithdrawal->id,
                'branch_name' => $stockWithdrawal->branch->name,
                'date' => formatDate($stockWithdrawal->date),
                'description' => $stockWithdrawal->description,
                'pic' => $stockWithdrawal->stockWithdrawalByEmployees->map(function ($stockWithdrawalByEmployee) {
                    return [
                        'id' => $stockWithdrawalByEmployee->id,
                        'user_id' => $stockWithdrawalByEmployee->user_id,
                        'name' => $stockWithdrawalByEmployee->user->name,
                    ];

                }),
                'stocker' => $stockWithdrawal->stocker?->name ?? null,
                'pic_signature_after_withdraw' => $stockWithdrawal->pic_signature_after_withdraw,
                'stocker_signature_after_withdraw' => $stockWithdrawal->stocker_signature_after_withdraw,
                'status' => $stockWithdrawal->status
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
                'branch_id' => Branch::where('id', $request->branch_id ?? $request->user()->branch_id)
                    ->first()?->parent_id,
                'date' => Carbon::now()->format('Y-m-d'),
                'description' => $request->input('description'),
                'stocker_id' => $request->user()->id,
            ]);
            $this->stockWithdrawalEmployeeStoreAndUpdate($stockWithdrawal, $request);
            $this->stockWithdrawalItemStoreOrUpdate($stockWithdrawal);
        });
    }


    public function stockWithdrawalItemStoreOrUpdate($stockWithdrawal): void
    {
        if (session()->has('stock_withdrawal_item')) {
            foreach (session('stock_withdrawal_item') as $item) {
                if (!empty($item['code'])) {
                    $itemCatalog = ItemCatalog::with('asset.depreciation')->where('code', $item['code'])->first();
                    $stock = Stock::where('id', $item['stock_id'])->first();
                    $itemCatalog->decrement('available_qty', $item['qty']);
                    $itemCatalog->update(['status' => 'Dibawa']);
                    $stock->decrement('available_qty', $item['qty']);
                    $stock->increment('on_hold_qty', $item['qty']);

                    StockWithdrawalItem::create([
                        'stock_withdrawal_id' => $stockWithdrawal->id,
                        'stock_id' => $item['stock_id'],
                        'code' => $item['code'],
                        'qty' => $item['qty'],
                    ]);
                }

                if (empty($item['code'])) {
                    $stock = Stock::where('id', $item['stock_id'])->first();
                    $stock->decrement('available_qty', $item['qty']);
                    $stock->increment('on_hold_qty', $item['qty']);

                    StockWithdrawalItem::create([
                        'stock_withdrawal_id' => $stockWithdrawal->id,
                        'stock_id' => $item['stock_id'],
                        'qty' => $item['qty'],
                    ]);
                }
            }
        }
        session()->forget('stock_withdrawal_item');
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

    public function getCarriedStock(): LengthAwarePaginator
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

    public function getStockWithdrawalItems(StockWithdrawal $stockWithdrawal)
    {
        $data = $this->stockWithdrawalRepository->getStockWithdrawalItems($stockWithdrawal)->get();

        return $data->map(function ($item) {
            return [
                'id' => $item->id,
                'item_name' => $item->stock->transaction?->item?->name ?? $item->stock->initialInventoryBalance->item->name,
                'code' => $item->code,
                'qty' => $item->qty,
                'status' => $item->status,
                'unit_type' => $item->stock->transaction->item->unitType->name ?? $item->stock->initialInventoryBalance->item->unitType->name,
                'returned_item' => $item->returnedItem,
                'category' => $item->stock->transaction?->item?->category?->name ?? $item->stock->initialInventoryBalance->item->category->name,
            ];
        });
    }


    /**
     * @throws Throwable
     */
    public function destroy(StockWithdrawal $stockWithdrawal): void
    {
        DB::transaction(function () use ($stockWithdrawal) {
            $data = $this->stockWithdrawalItemRepository->findByStockWithdrawal($stockWithdrawal);
            $withdrawalItems = [];
            foreach ($data->get() as $withdrawalItem) {
                foreach ($withdrawalItem->stock->itemCatalog as $itemCatalog) {
                    $withdrawalItems [] = $itemCatalog->where('stock_id', $withdrawalItem->stock_id)
                        ->where('code', $withdrawalItem->code)
                        ->where('status', 'Dibawa')
                        ->pluck('id')->toArray();
                }
            }
            ItemCatalog::whereIn('id', $withdrawalItems)->update(['status' => 'Tersedia',]);
            $stockWithdrawal->delete();
        });
    }
}
