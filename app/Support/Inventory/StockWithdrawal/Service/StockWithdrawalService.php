<?php

namespace App\Support\Inventory\StockManagement\StockWithdrawal\Service;

namespace App\Support\Inventory\StockWithdrawal\Service;

use AllowDynamicProperties;
use App\Http\Requests\StockWithdrawalRequest;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Models\StockWithdrawal;
use App\Models\StockWithdrawalByEmployee;
use App\Models\StockWithdrawalItem;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\Inventory\StockManagement\StockWithdrawal\Repository\StockWithdrawalItemRepository;
use App\Support\Inventory\StockManagement\StockWithdrawal\Repository\StockWithdrawalRepository;
use App\Support\Inventory\StockManagement\StockWithdrawal\Service\StockWithdrawalQueryFilter;
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
    }

    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $data = $this->stockWithdrawalRepository->getStockWithdrawalQuery()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->stockWithdrawalRepository->getStockWithdrawalQuery();
        if (!empty($search)) {
            $query = $this->stockWithdrawalRepository->searchQuery($query, $search);
        }

        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->stockWithdrawalRepository->getStockWithdrawalQuery();
        $query = StockWithdrawalQueryFilter::apply($query, $request)->paginate(self::$perPage);
        return self::formattedData($query);
    }


    private static function formattedData(LengthAwarePaginator $stockWithdrawal): LengthAwarePaginator
    {
        $data = $stockWithdrawal->getCollection()->map(function ($stockWithdrawal) {
            return [
                'id' => $stockWithdrawal->id,
                'branch_name' => $stockWithdrawal->branch->name,
                'date' => formatDate($stockWithdrawal->date),
                'description' => $stockWithdrawal->description,
                'pic' => $stockWithdrawal->pic?->name ?? null,
                'stocker' => $stockWithdrawal->stocker?->name ?? null,
                'pic_signature_after_withdraw' => $stockWithdrawal->pic_signature_after_withdraw,
                'stocker_signature_after_withdraw' => $stockWithdrawal->stocker_signature_after_withdraw,
                'status' => $stockWithdrawal->status
            ];
        });

        $stockWithdrawal->setCollection($data);
        return $stockWithdrawal;
    }


    public function showStockWithdrawalItems(StockWithdrawal $stockWithdrawal)
    {
        return $stockWithdrawal->stockWithdrawalItems->map(function ($item) {
            return [
                'item_name' => $item->stock->item->name,
                'code' => $item->code,
                'qty' => $item->qty,
            ];
        });
    }


    public function showStockWithdrawalByEmployees(StockWithdrawal $stockWithdrawal)
    {
        return $stockWithdrawal->stockWithdrawalByEmployees->map(function ($item) {
            return [
                'name' => $item->user->name,
                'roles' => $item->user->roles->pluck('name')->implode(', '),
                'nik' => $item->user->nip,
                'profile_pic' => $item->user->profile_pic,
            ];
        });
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
                'stocker_id' => $request->user()->id,
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
                Stock::where('id', $itemCatalog->stock_id)->decrement('qty');
                ItemCatalog::create([
                    'transaction_id' => $itemCatalog->transaction_id,
                    'stock_id' => $itemCatalog->stock_id,
                    'code' => $itemCatalog->code,
                    'draft_stock_id' => $itemCatalog->draft_stock_id,
                    'condition' => $itemCatalog->condition,
                    'item_id' => $itemCatalog->item_id,
                    'created_by' => $itemCatalog->created_by,
                    'asset_id' => $itemCatalog->asset_id,
                    'qty_in_meter' => $itemCatalog->qty_in_meter,
                    'status' => 'Dibawa'
                ]);
                $itemCatalog->delete();
                StockWithdrawalItem::create([
                    'stock_withdrawal_id' => $stockWithdrawal->id,
                    'stock_id' => $itemCatalog->stock_id,
                    'code' => $itemCatalog->code,
                    'status' => 'Dibawa',
                    'qty' => 1,
                ]);
            }
        }

        if ($request['itemWithoutCodeFields']) {
            foreach ($request['itemWithoutCodeFields'] as $key => $value) {
                $stockWithoutCode = Stock::with('item', 'itemCatalog')->where('id', $value['stock_id'])->first();
                $stockWithoutCode->decrement('qty', $value['qty']);
                $stockWithoutCode->increment('on_hold_qty', $value['qty']);
                $value['stock_withdrawal_id'] = $stockWithdrawal->id;
                $value['stock_id'] = $stockWithoutCode->id;
                $value['status'] = 'Dibawa';
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


    public function confirmedByPIC(StockWithdrawal $stockWithdrawal): void
    {
        $stockWithdrawal->load('stockWithdrawalItems', 'stockWithdrawalByEmployees', 'stockWithdrawalItems.stock.item', 'stockWithdrawalByEmployees.user.roles');


        $hash = Hash::make($stockWithdrawal->id);

        $image = QrCode::format('png')->size(200)->generate($hash);

        $signaturePath = 'documents/stock-withdrawal/pic-signature/' . $hash . '.png';
        Storage::disk('public')->put($signaturePath, $image);

        $stockWithdrawal->update([
            'pic_id' => Auth::id(),
            'pic_signature_after_withdraw' => $signaturePath,
        ]);
    }


    public function confirmedByStocker(StockWithdrawal $stockWithdrawal): void
    {
        $stockWithdrawal->load('stockWithdrawalItems', 'stockWithdrawalByEmployees', 'stockWithdrawalItems.stock.item', 'stockWithdrawalByEmployees.user.roles');

        $hash = Hash::make($stockWithdrawal->id);
        $image = QrCode::format('png')->size(200)
            ->generate($hash);

        $signaturePath = 'documents/stock-withdrawal/stocker-signature/' . $hash . '.png';
        Storage::disk('public')->put($signaturePath, $image);

        $stockWithdrawal->update([
            'stocker_id' => Auth::id(),
            'stocker_signature_after_withdraw' => $signaturePath,
            'status' => 'Dibawa'
        ]);
    }


    public function getStockWithdrawalItems(StockWithdrawal $stockWithdrawal)
    {
        $data = $this->stockWithdrawalRepository->getStockWithdrawalItems($stockWithdrawal)->get();

        return $data->map(function ($item) {
            return [
                'id' => $item->id,
                'item_name' => $item->stock->item->name,
                'code' => $item->code,
                'qty_in_meter' => ItemCatalog::where('code', $item->code)->first()->qty_in_meter,
                'qty' => $item->qty,
                'status' => $item->status,
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
