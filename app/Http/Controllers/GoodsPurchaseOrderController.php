<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\GoodsPurchaseOrderRequest;
use App\Models\Branch;
use App\Models\BranchWarehouseItem;
use App\Models\CentralWarehouseItem;
use App\Models\Goods;
use App\Models\GoodsCategory;
use App\Models\GoodsPurchaseOrder;
use App\Models\ItemTransaction;
use App\Models\ReturnItemFromPo;
use App\Models\Supplier;
use App\Models\UnitType;
use App\Models\Warehouse;
use App\Service\CentralWareHouseStockService;
use App\Service\GoodsPurchaseOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class GoodsPurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->supplier = new Supplier();
        $this->goodsPurchaseOrder = new GoodsPurchaseOrderService();
        $this->branch = new Branch();
        $this->itemCategory = new GoodsCategory();
        $this->centralWareHouseStockService = new CentralWareHouseStockService();
        $this->item = new Goods();
        $this->unitType = new UnitType();
        $this->warehouse = new Warehouse();
    }

    public function index(): View
    {
        return view('pages.inventory.goods.po.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->goodsPurchaseOrder->data());
    }


    public function search(): JsonResponse
    {
        return response()->json();
    }

    public function create(): View
    {
        return view('pages.inventory.goods.po.form');
    }


    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }


    public function getWarehouseData(Request $request)
    {
        return response()->json($this->warehouse->getData($request));
    }


    public function getSupplierData(Request $request): JsonResponse
    {
        return response()->json($this->supplier->getData($request));
    }


    public function store(GoodsPurchaseOrderRequest $request): JsonResponse
    {

        $ppn = $this->goodsPurchaseOrder->getPPN();
        $total_price = $request->qty * $request->unit_price;

        GoodsPurchaseOrder::create([
            'warehouse_id' => $request->warehouse_id,
            'branch_id' => $request->branch_id,
            'po_number' => $request->po_number,
            'invoice_number' => $request->invoice_number,
            'item_id' => $request->item_id,
            'date' => $request->date,
            'unit_price' => $request->unit_price,
            'qty' => $request->qty,
            'shipping_cost' => $request->shipping_cost,
            'ppn' => $request->ppn === "0" ? $ppn / 100 * $total_price : null,
            'total_price' => $total_price,
            'supplier_id' => $request->supplier_id,
            'travel_letter_receipt' => $request->travel_letter_receipt,
        ]);

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function selectedSupplier(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        return response()->json($this->supplier->getSelectedData($goodsPurchaseOrder->supplier_id));
    }

    public function selectedBranch(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($goodsPurchaseOrder->branch_id));
    }

    public function edit(GoodsPurchaseOrder $goodsPurchaseOrder): View
    {
        return view('pages.inventory.goods.po.form', compact('goodsPurchaseOrder'));
    }

    public function detail(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        return response()->json([
            'id' => $goodsPurchaseOrder->id,
            'branch_id' => $goodsPurchaseOrder->branch_id,
            'branch_name' => $goodsPurchaseOrder->branch?->name,
            'po_number' => $goodsPurchaseOrder->po_number,
            'invoice_number' => $goodsPurchaseOrder->invoice_number,
            'name' => $goodsPurchaseOrder->item->name,
            'date' => formatDate($goodsPurchaseOrder->date),
            'unit_price' => number_format($goodsPurchaseOrder->unit_price),
            'qty' => $goodsPurchaseOrder->qty,
            'shipping_cost' => number_format($goodsPurchaseOrder->shipping_cost),
            'ppn' => number_format($goodsPurchaseOrder->ppn),
            'total_price' => number_format($goodsPurchaseOrder->total_price, 1),
            'supplier_id' => $goodsPurchaseOrder->supplier?->name,
            'travel_letter_receipt' => $goodsPurchaseOrder->travel_letter_receipt,
        ]);
    }

    public function update(GoodsPurchaseOrderRequest $request, GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        $ppn = $this->goodsPurchaseOrder->getPPN();
        $total_price = $request->qty * $request->unit_price;

        $goodsPurchaseOrder->update([
            'warehouse_id' => $request->warehouse_id,
            'branch_id' => $request->branch_id,
            'name' => $request->item_id,
            'date' => $request->date,
            'unit_price' => $request->unit_price,
            'qty' => $request->qty,
            'shipping_cost' => $request->shipping_cost,
            'ppn' => $request->ppn === "0" ? $ppn / 100 * $total_price : null,
            'total_price' => $total_price,
            'supplier_id' => $request->supplier_id,
            'travel_letter_receipt' => $request->travel_letter_receipt,
        ]);

        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function confirm(Request $request, GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
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

            if ($request->warehouse_id !== null) {
                DB::transaction(function () use ($goodsPurchaseOrder, $request) {
                    CentralWarehouseItem::create([
                        'date' => $request->date,
                        'po_id' => $goodsPurchaseOrder->id,
                        'warehouse_id' => $request->warehouse_id,
                        'item_id' => $goodsPurchaseOrder->item_id,
                        'qty' => $request->qty_can_be_used,
                    ]);

                    ItemTransaction::create([
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
            } else {
                BranchWarehouseItem::create([
                    'branch_id' => $goodsPurchaseOrder->branch_id,
                    'date' => $request->date,
                    'po_id' => $goodsPurchaseOrder->id,
                    'item_id' => $goodsPurchaseOrder->item_id,
                    'qty' => $request->qty_can_be_used,
                ]);


                ItemTransaction::create([
                    'date' => $request->date,
                    'po_id' => $goodsPurchaseOrder->id,
                    'item_id' => $goodsPurchaseOrder->item_id,
                    'branch_id' => $goodsPurchaseOrder->branch_id,
                    'warehouse_id' => $request->warehouse_id,
                    'type' => 'in',
                    'qty' => $request->qty_can_be_used,
                    'from_po' => true,
                ]);
            }
        });

        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function destroy()
    {

    }


    public function getItemCategories(Request $request): JsonResponse
    {
        return response()->json($this->itemCategory->getData($request));
    }

    public function getItem(Request $request): JsonResponse
    {
        return response()->json($this->item->getData($request));
    }

    public function selectedItem(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        return response()->json($this->item->getSelectedData($goodsPurchaseOrder->item_id));
    }


    public function selectedWarehouse(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        return response()->json($this->warehouse->getSelectedData($goodsPurchaseOrder->warehouse_id));
    }
}
