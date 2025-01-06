<?php

namespace App\Service;

use App\Models\BranchWarehouseItem;
use App\Models\BranchWarehouseStock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class BranchWarehouseStockService
{
    private static int $perPage = 10;


    public function generateCodeWithNumber(BranchWarehouseItem $branchWarehouseItem, $number)
    {
        $itemName = $branchWarehouseItem->item->name ?? 'UnknownItem';
        $warehouseCode = $branchWarehouseItem->branch->code ?? 'UnknownWarehouse';
        $itemSlug = Str::slug($itemName, '');
        $warehouseSlug = Str::slug($warehouseCode, '');
        $dateIn = Carbon::parse($branchWarehouseItem->date)->format('my');
        $paddedNumber = str_pad($number + 1, 2, '0', STR_PAD_LEFT);

        return $dateIn . strtoupper($itemSlug) . strtoupper($warehouseSlug) . $paddedNumber;
    }

    public function data(BranchWarehouseItem $branchWarehouseItem)
    {
        return BranchWarehouseStock::where('branch_warehouse_item_id', $branchWarehouseItem->id)
            ->paginate(self::$perPage);
    }

    public function generateSNIfExists(BranchWarehouseItem $branchWarehouseItem, Request $request): void
    {
        DB::transaction(function () use ($branchWarehouseItem, $request) {
            if ($branchWarehouseItem->qty <= 0) {
                return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
            }

            BranchWarehouseStock::create([
                'po_item_id' => $branchWarehouseItem->po_item_id,
                'branch_warehouse_item_id' => $branchWarehouseItem->id,
                'sn' => $request->sn,
            ]);
            $branchWarehouseItem->decrement('qty');
        });
    }

    /**
     * @throws Throwable
     */
    public function generateSNIfNotExists(BranchWarehouseItem $branchWarehouseItem): void
    {
        DB::transaction(function () use ($branchWarehouseItem) {
            if ($branchWarehouseItem->qty <= 0) {
                return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
            }

            $stocks = [];
            for ($i = 0; $i < $branchWarehouseItem->qty; $i++) {
                $stocks[] = [
                    'branch_warehouse_item_id' => $branchWarehouseItem->id,
                    'sn' => $this->generateCodeWithNumber($branchWarehouseItem, $i),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            BranchWarehouseStock::insert($stocks);
            $branchWarehouseItem->update(['qty' => 0]);
        });
    }
}
