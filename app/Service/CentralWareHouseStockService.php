<?php

namespace App\Service;

use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
use App\Models\GoodsTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class CentralWareHouseStockService
{


    private static int $perPage = 10;

    public function generateCodeWithNumber(CentralWarehouseItem $centralWarehouseItem, $number): string
    {
        $itemName = $centralWarehouseItem->item->name ?? 'UnknownItem';
        $warehouseCode = $centralWarehouseItem->warehouse->code ?? 'UnknownWarehouse';
        $itemSlug = Str::slug($itemName, '');
        $warehouseSlug = Str::slug($warehouseCode, '');
        $dateIn = Carbon::parse($centralWarehouseItem->date)->format('my');
        $paddedNumber = str_pad($number + 1, 2, '0', STR_PAD_LEFT);

        return $dateIn . "." . strtoupper($itemSlug) . "." . strtoupper($warehouseSlug) . "." . $paddedNumber;
    }


    public function data(GoodsTransaction $itemTransaction): LengthAwarePaginator
    {
        return CentralWarehouseStock::where('po_id', $itemTransaction->po_id)->where('warehouse_id', $itemTransaction->warehouse_id)
            ->paginate(self::$perPage);
    }


    /**
     * @throws Throwable
     */
    public function generateSNIfExists(CentralWarehouseItem $centralWarehouseItem, Request $request): void
    {
        DB::transaction(function () use ($centralWarehouseItem, $request) {
            if ($centralWarehouseItem->qty <= 0) {
                return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
            }

            CentralWarehouseStock::create([
                'po_id' => $centralWarehouseItem->po_id,
                'warehouse_id' => $centralWarehouseItem->warehouse_id,
                'item_id' => $centralWarehouseItem->item_id,
                'sn' => $request->sn,
            ]);
            $centralWarehouseItem->decrement('qty');
        });
    }

    /**
     * @throws Throwable
     */
    public function generateSNIfNotExists(CentralWarehouseItem $centralWarehouseItem): void
    {
        DB::transaction(function () use ($centralWarehouseItem) {
            if ($centralWarehouseItem->qty <= 0) {
                return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
            }


            $chunkSize = 1000;

            $stocks = [];
            for ($i = 0; $i < $centralWarehouseItem->qty; $i++) {
                $stocks[] = [
                    'branch_warehouse_item_id' => $centralWarehouseItem->id,
                    'sn' => $this->generateCodeWithNumber($centralWarehouseItem, $i),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                if (count($stocks) >= $chunkSize) {
                    CentralWarehouseItem::insert($stocks);
                    $stocks = [];
                }
            }


            if (!empty($stocks)) {
                CentralWarehouseItem::insert($stocks);
            }
            $centralWarehouseItem->update(['qty' => 0]);
        });
    }


    /**
     * @throws Throwable
     */
    public function destroy(Request $request, CentralWarehouseStock $centralWarehouseStock): void
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);

        DB::transaction(function () use ($centralWarehouseStock, $explodeID) {
            $centralWarehouseItemId = CentralWarehouseStock::find($explodeID[0])->central_warehouse_item_id;
            CentralWarehouseItem::where('id', $centralWarehouseItemId)->increment('qty', count($explodeID));
            $centralWarehouseStock->whereIn('id', $explodeID)->delete();
        });
    }

    public function search(Request $request, CentralWarehouseItem $centralWarehouseItem): LengthAwarePaginator
    {

        $search = $request->input('search');
        return CentralWarehouseStock::where('central_warehouse_item_id', $centralWarehouseItem->id)
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('sn', 'like', '%' . $search . '%')->orWhere('code', 'like', '%' . $search . '%');
            })->paginate(self::$perPage);
    }

}
