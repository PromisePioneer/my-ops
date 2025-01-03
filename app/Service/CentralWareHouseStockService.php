<?php

namespace App\Service;

use App\Models\CentralWarehouseItem;
use App\Models\CentralWarehouseStock;
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


    public function data(CentralWarehouseItem $centralWarehouseItem): LengthAwarePaginator
    {
        return CentralWarehouseStock::where('central_warehouse_item_id', $centralWarehouseItem->id)
            ->paginate(self::$perPage);
    }


    /**
     * @throws Throwable
     */
    public function generateSNAndCode(CentralWarehouseItem $centralWarehouseItem, Request $request): void
    {
        DB::transaction(function () use ($centralWarehouseItem, $request) {
            if ($centralWarehouseItem->qty <= 0) {
                return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
            }

            $lastStock = CentralWarehouseStock::where('central_warehouse_item_id', $centralWarehouseItem->id)
                ->lockForUpdate()
                ->latest()
                ->first();

            $array = explode('.', $lastStock?->code);
            $startNumber = $lastStock ? (int)end($array) : 0;

            CentralWarehouseStock::create([
                'central_warehouse_item_id' => $centralWarehouseItem->id,
                'sn' => $request->sn,
                'code' => $this->generateCodeWithNumber($centralWarehouseItem, $startNumber),
            ]);
            $centralWarehouseItem->decrement('qty');
        });
    }

    /**
     * @throws Throwable
     */
    public function generateCentralWarehouseItemCodeIfSNDoesntExists(CentralWarehouseItem $centralWarehouseItem): void
    {
        DB::transaction(function () use ($centralWarehouseItem) {
            if ($centralWarehouseItem->qty <= 0) {
                return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
            }

            $stocks = [];
            for ($i = 0; $i < $centralWarehouseItem->qty; $i++) {
                $stocks[] = [
                    'central_warehouse_item_id' => $centralWarehouseItem->id,
                    'code' => $this->generateCodeWithNumber($centralWarehouseItem, $i),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            CentralWarehouseStock::insert($stocks);
            $centralWarehouseItem->update(['qty' => 0]);
        });
    }


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



}
