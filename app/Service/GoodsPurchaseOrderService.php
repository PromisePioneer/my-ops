<?php

namespace App\Service;

use App\Models\Goods;
use App\Models\GoodsPurchaseOrder;
use App\Models\GoodsStock;
use App\Models\GoodsTransaction;
use App\Models\PurchaseOrderDetail;
use App\Models\ReturnItemFromPo;
use App\Models\TaxSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use function App\Helper\formatDate;

class GoodsPurchaseOrderService
{
    private static int $perPage = 10;

    public function query(): Builder
    {
        return GoodsPurchaseOrder::with('supplier', 'branch', 'warehouse');
    }

    public function data(): LengthAwarePaginator
    {
        $data = $this->query()->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = $this->query()->when(!empty($search), function ($query) use ($search) {
            $query->where('po_number', 'like', '%' . $search . '%')
                ->orWhere('invoice_number', 'like', '%' . $search . '%')
                ->orWhereHas('item', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })->orWhere('qty', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);


        return self::formattedData($data);
    }


    private static function formattedData(LengthAwarePaginator $goodsPurchaseOrder): LengthAwarePaginator
    {
        $data = $goodsPurchaseOrder->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'po_number' => $item->po_number,
                'invoice_number' => $item->invoice_number,
                'location' => $item->branch?->name ?? $item->warehouse?->name,
                'date' => formatDate($item->date),
                'name' => $item->item->name,
                'qty' => $item->qty,
                'unit_price' => 'Rp ' . number_format($item->unit_price, 2),
                'shipping_cost' => $item->shipping_cost,
                'supplier' => $item->supplier?->name,
                'ppn' => $item->ppn,
                'status' => $item->status,
                'total_price' => $item->total_price,
                'travel_letter_receipt' => $item->travel_letter_receipt,
            ];
        });

        $goodsPurchaseOrder->setCollection($data);
        return $goodsPurchaseOrder;
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $branchId = $request->branch_id;
        $warehouseId = $request->warehouse_id;
        $month = $request->month;
        $year = $request->year;
        $data = $this->query();
        if ($branchId) {
            $data->whereHas('branch', function ($query) use ($branchId) {
                $query->where('id', $branchId);
            })->whereMonth('date', $month)->whereYear('date', $year);
        }

        if ($warehouseId) {
            $data->whereHas('warehouse', function ($query) use ($warehouseId) {
                $query->where('id', $warehouseId);
            })->whereMonth('date', $month)->whereYear('date', $year);
        }


        return $data->paginate(self::$perPage);
    }


    public function getPPN()
    {
        return TaxSetting::where('name', 'PPN')->first()?->rate;
    }


    public function confirm(Request $request, GoodsPurchaseOrder $goodsPurchaseOrder): void
    {
        DB::transaction(function () use ($request, $goodsPurchaseOrder) {
            $goodsPurchaseOrder->update([
                'status' => 1
            ]);

            if ($request->qty_cannot_be_used > 0) {
                ReturnItemFromPo::create([
                    'po_id' => $goodsPurchaseOrder->id,
                    'item_id' => $goodsPurchaseOrder->item_id,
                    'qty' => $request->qty_cannot_be_used,
                    'reason' => $request->reason
                ]);
            }


            $item = Goods::where('id', $goodsPurchaseOrder->item_id)->first();

            if ($item->need_sn === 0 && $item->already_has_sn_on_item === 0) {
                GoodsStock::create([
                    'po_id' => $goodsPurchaseOrder->id,
                    'warehouse_id' => $goodsPurchaseOrder->warehouse_id,
                    'branch_id' => $goodsPurchaseOrder->branch_id,
                    'item_id' => $goodsPurchaseOrder->item_id,
                    'qty' => $goodsPurchaseOrder->qty,
                    'status' => true
                ]);
            }

            PurchaseOrderDetail::create([
                'date' => $request->date,
                'po_id' => $goodsPurchaseOrder->id,
                'item_id' => $goodsPurchaseOrder->item_id,
                'qty' => $request->qty_can_be_used,
            ]);

            GoodsTransaction::create([
                'date' => $request->date,
                'po_id' => $goodsPurchaseOrder->id,
                'item_id' => $goodsPurchaseOrder->item_id,
                'branch_id' => $goodsPurchaseOrder->branch_id,
                'warehouse_id' => $request->warehouse_id,
                'type' => 'in',
                'qty' => $request->qty_can_be_used,
                'from_po' => true,
            ]);
        });
    }


}
