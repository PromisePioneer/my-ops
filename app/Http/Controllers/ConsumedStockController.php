<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsedStockRequest;
use App\Models\Account;
use App\Models\Asset;
use App\Models\ConsumedStock;
use App\Models\Goods;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class ConsumedStockController extends Controller
{
    public function index(): View
    {
        return view('used_stock.index');
    }


    public function assetAccount(Request $request)
    {
        $search = $request->input('search');
        $account = Account::whereBetween('code', ['121', '126']);

        if ($search !== '') {
            $account->whereBetween('code', ['121', '126'])
                ->where('name', 'like', '%' . $search . '%');
        }

        return $account->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }

    /**
     * @throws Throwable
     */
    public function store(UsedStockRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $stockId = Stock::where('branch_id', $request->branch_id)
                ->where('item_id', $request->goods_id)
                ->first()->id;
            $data = $request->validated();
            $branchId = $request->branch_id ?? $request->user()->branch_id;
            $data['stock_id'] = $stockId;
            $data['branch_id'] = empty($request->user()->branch_id)
                ? $request->input('branch_id')
                : $request->user()->branch_id;
            $data['submitted_by'] = $request->user()->id;
            ConsumedStock::create($data);

            Stock::where('branch_id', $branchId)
                ->where('item_id', $request->goods_id)
                ->decrement('qty', $request->qty);

            $goods = Goods::with('category')
                ->where('id', $request->input('goods_id'))
                ->first();

            $goodsStock = Stock::with('transaction')
                ->where('branch_id', $branchId)
                ->where('item_id', $request->goods_id)->first();


            if ($goods->category->name === 'ASET') {
                $asset = Asset::where('branch_id', $branchId)->where('name', $goods->name)->first();
                if ($asset) {
                    $asset->increment('unit', $request->qty);
                } else {
                    $usefulLife = $this->usefulLife(Account::where('id', $request->debit_account_id)->first()->code, $goods->material);
                    Asset::create([
                        'branch_id' => $branchId,
                        'debit_account_id' => $request->debit_account_id,
                        'credit_account_id' => $request->credit_account_id,
                        'date_received' => Carbon::now(),
                        'name' => $goods->name,
                        'price_per_unit' => $goodsStock->transaction->unit_price,
                        'unit' => $request->qty,
                        'total_price' => $goodsStock->transaction->unit_price * $request->qty,
                        'useful_life' => $usefulLife
                    ]);
                }

            }
        });

        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

    public function usefulLife($code, $goodsMaterial): ?int
    {
        if ($code === '121') {
            return null;
        }

        if ($code === '122') {
            return 20;
        }

        if ($code === '123' || $code === '124') {
            return 8;
        }

        if ($code === '125' && $goodsMaterial === 'Besi') {
            return 8;
        }

        if ($code === '125' && $goodsMaterial === '8 Tahun') {
            return 8;
        }
        return null;
    }


    public function usedStockHistoryDetail(Goods $goods): View
    {
        return view('pages.inventory.goods.stocks.used-stock-detail', compact('goods'));
    }
}
