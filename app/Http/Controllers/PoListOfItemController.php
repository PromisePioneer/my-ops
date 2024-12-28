<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\PoListOfItemRequest;
use App\Models\Branch;
use App\Models\CentralWarehouseStock;
use App\Models\ItemCategory;
use App\Models\PoListOfItem;
use App\Models\Supplier;
use App\Service\CentralWareHouseStockService;
use App\Service\PoListOfItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class PoListOfItemController extends Controller
{
    public function __construct()
    {
        $this->supplier = new Supplier();
        $this->poListOfItemService = new PoListOfItemService();
        $this->branch = new Branch();
        $this->itemCategory = new ItemCategory();
        $this->centralWareHouseStockService = new CentralWareHouseStockService();
    }

    public function index(): View
    {
        return view('pages.inventory.list-of-items.po.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->poListOfItemService->data());
    }


    public function search(): JsonResponse
    {
        return response()->json();
    }

    public function create(): View
    {
        return view('pages.inventory.list-of-items.po.create');
    }


    public function getSupplierData(Request $request): JsonResponse
    {
        return response()->json($this->supplier->getData($request));
    }


    public function store(PoListOfItemRequest $request): JsonResponse
    {
        $ppn = $this->poListOfItemService->getPPN();
        $total_price = $request->qty * $request->unit_price;

        PoListOfItem::create([
            'po_number' => $request->po_number,
            'invoice_number' => $request->invoice_number,
            'name' => $request->name,
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

    public function selectedSupplier(PoListOfItem $poListOfItem): JsonResponse
    {
        return response()->json($this->supplier->getSelectedData($poListOfItem->id));
    }

    public function selectedBranch(PoListOfItem $poListOfItem): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($poListOfItem->branch_id));
    }

    public function edit(PoListOfItem $poListOfItem): View
    {
        return view('pages.inventory.list-of-items.po.edit', compact('poListOfItem'));
    }

    public function detail(PoListOfItem $poListOfItem): JsonResponse
    {
        return response()->json([
            'id' => $poListOfItem->id,
            'po_number' => $poListOfItem->po_number,
            'invoice_number' => $poListOfItem->invoice_number,
            'name' => $poListOfItem->name,
            'date' => formatDate($poListOfItem->date),
            'unit_price' => number_format($poListOfItem->unit_price),
            'qty' => $poListOfItem->qty,
            'shipping_cost' => number_format($poListOfItem->shipping_cost),
            'ppn' => number_format($poListOfItem->ppn),
            'total_price' => number_format($poListOfItem->total_price, 1),
            'supplier_id' => $poListOfItem->supplier?->name,
            'travel_letter_receipt' => $poListOfItem->travel_letter_receipt,
        ]);
    }

    public function update(PoListOfItemRequest $request, PoListOfItem $poListOfItem): JsonResponse
    {


        $ppn = $this->poListOfItemService->getPPN();
        $total_price = $request->qty * $request->unit_price;


        $poListOfItem->update([
            'name' => $request->name,
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


    public function confirm(Request $request, PoListOfItem $poListOfItem): JsonResponse
    {
        $poListOfItem->update([
            'status' => 1
        ]);

        CentralWarehouseStock::create([
            'sn' => $this->centralWareHouseStockService->generateSN($poListOfItem),
            'po_items_id' => $poListOfItem->id,
            'name' => $poListOfItem->name,
            'qty' => $request->qty,
            'category_id' => $request->category_id
        ]);

        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function destroy()
    {

    }


    public function getItemCategories(Request $request): JsonResponse
    {
        return response()->json($this->itemCategory->getData($request));
    }
}
