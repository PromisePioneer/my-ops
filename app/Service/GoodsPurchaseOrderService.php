<?php

namespace App\Service;

use App\Models\BranchWarehouseItem;
use App\Models\CentralWarehouseItem;
use App\Models\GoodsPurchaseOrder;
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
        return GoodsPurchaseOrder::with('supplier', 'branch');
    }

    public function data(): LengthAwarePaginator
    {
        $data = $this->query()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    private static function formattedData(LengthAwarePaginator $listOfItem): LengthAwarePaginator
    {
        $data = $listOfItem->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'sn' => $item->sn,
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

        $listOfItem->setCollection($data);
        return $listOfItem;
    }

    public function search(Request $request)
    {

        $data = $this->query()->when('');


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
