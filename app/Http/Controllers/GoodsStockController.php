<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\GenerateItemSNRequest;
use App\Models\Goods;
use App\Models\GoodsPurchaseOrder;
use App\Models\GoodsStock;
use App\Models\GoodsTransaction;
use App\Service\GoodsStockService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

#[AllowDynamicProperties] class GoodsStockController extends Controller
{

    private static int $perPage = 10;


    public function __construct()
    {
        $this->goodsStockService = new GoodsStockService();
    }


    public function index(): View
    {
        return view('pages.inventory.goods.stocks.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->goodsStockService->goodsData());
    }


    public function goodsSearch(Request $request): JsonResponse
    {
        return response()->json($this->goodsStockService->searchGoodsData($request));
    }

    public function goodsFilter(Request $request): JsonResponse
    {
        return response()->json($this->goodsStockService->filter($request));
    }

    public function detail(Goods $goods): View
    {
        return view('pages.inventory.goods.stocks.detail.index', compact('goods'));
    }

    public function getPO(Goods $goods): JsonResponse
    {
        return response()->json($this->goodsStockService->getPO($goods));
    }

    public function searchPO(Request $request, Goods $goods): JsonResponse
    {
        return response()->json($this->goodsStockService->searchPO($request, $goods));
    }


    public function generateSN(GoodsPurchaseOrder $goodsPurchaseOrder): View
    {
        return view('pages.inventory.goods.stocks.generate-sn.index', compact('goodsPurchaseOrder'));
    }


    public function PODetail(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        $goodsPurchaseOrder = GoodsPurchaseOrder::with('item')->find($goodsPurchaseOrder->id);
        return response()->json($goodsPurchaseOrder);
    }


    public function getGoodsStockBasedOnPO(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        $data = GoodsStock::where('po_id', $goodsPurchaseOrder->id)->with('createdBy')->paginate(self::$perPage);
        return response()->json($data);
    }


    public function confirmGoodsReceived(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        DB::transaction(function () use ($goodsPurchaseOrder) {

            $goodsPurchaseOrder->update([
                'status_received' => true,
                'received_by' => Auth::id()
            ]);

            GoodsTransaction::find($goodsPurchaseOrder->id)->update([
                'status_received' => true,
                'received_by' => Auth::id()
            ]);
        });

        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    public function createSN(GoodsPurchaseOrder $goodsPurchaseOrder, GenerateItemSNRequest $request): JsonResponse
    {
        if ($goodsPurchaseOrder->item->need_sn === 1 && $goodsPurchaseOrder->item->already_has_sn_on_item === 1) {
            GoodsStock::create([
                'po_id' => $goodsPurchaseOrder->id,
                'warehouse_id' => $goodsPurchaseOrder->warehouse_id,
                'branch_id' => $goodsPurchaseOrder->branch_id,
                'item_id' => $goodsPurchaseOrder->item_id,
                'qty' => $goodsPurchaseOrder->qty,
                'sn' => $request->sn,
                'created_by' => Auth::id(),
            ]);
        }

        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    public function autoCreateSN(GoodsPurchaseOrder $goodsPurchaseOrder): JsonResponse
    {
        if ($goodsPurchaseOrder->item->need_sn === 1 && $goodsPurchaseOrder->item->already_has_sn_on_item === 0) {
            DB::transaction(function () use ($goodsPurchaseOrder) {
                if ($goodsPurchaseOrder->qty <= 0) {
                    return response()->json(['message' => 'Barang yang belum terdaftar sudah habis'], 403);
                }

                $chunkSize = 1000;
                for ($i = 0; $i < $goodsPurchaseOrder->qty; $i++) {
                    $stocks[] = [
                        'po_id' => $goodsPurchaseOrder->id,
                        'warehouse_id' => $goodsPurchaseOrder->warehouse_id,
                        'branch_id' => $goodsPurchaseOrder->branch_id,
                        'item_id' => $goodsPurchaseOrder->item_id,
                        'sn' => $this->generateCodeWithNumber($goodsPurchaseOrder, $i),
                        'created_by' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];


                    if (count($stocks) >= $chunkSize) {
                        GoodsStock::insert($stocks);
                        $stocks = [];
                    }
                }
                GoodsStock::insert($stocks);
            });
        }

        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    public function generateCodeWithNumber(GoodsPurchaseOrder $goodsPurchaseOrder, $number): string
    {
        $itemName = $goodsPurchaseOrder->item->name ?? 'UnknownItem';
        $warehouseCode = $goodsPurchaseOrder->warehouse->code ?? 'UnknownWarehouse';
        $itemSlug = Str::slug($itemName, '');
        $warehouseSlug = Str::slug($warehouseCode, '');
        $dateIn = Carbon::parse($goodsPurchaseOrder->date)->format('my');
        $paddedNumber = str_pad($number + 1, 2, '0', STR_PAD_LEFT);
        return $dateIn . "." . strtoupper($itemSlug) . "." . strtoupper($warehouseSlug) . "." . $paddedNumber;
    }


    public function edit(GoodsStock $goodsStock): JsonResponse
    {
        return response()->json($goodsStock);
    }


    public function update(GoodsStock $goodsStock, GenerateItemSNRequest $request): JsonResponse
    {
        $goodsStock->update([
            'sn' => $request->sn
        ]);

        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function destroy(Request $request, GoodsStock $goodsStock): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goodsStock->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

    public function confirm(GoodsStock $goodsStock, Request $request): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $goodsStock->whereIn('id', $explodeID)->update(['status' => 1]);

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
