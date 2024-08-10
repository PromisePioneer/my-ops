<?php

namespace App\Service;

use App\Models\Goods;
use App\Models\UsedItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsedItemServices
{
    private const ACCOUNT_TRANSACTIONS_IF_ITEM_IS_ASSET_DESCRIPTION = 'Pemakaian barang %s %s';

    private AccountTransactionService $accountTransactionService;

    public function __construct()
    {
        $this->accountTransactionService = new AccountTransactionService();
    }

    public function store(Request $request, Goods $goods): void
    {
        $description = sprintf(self::ACCOUNT_TRANSACTIONS_IF_ITEM_IS_ASSET_DESCRIPTION, $goods->name, $request->total_used);
        $amount = $goods->unit_price * $request->total_used;

        DB::transaction(function () use ($request, $goods, $description, $amount) {
            $this->isQtyGreaterThanZero($goods, $request);
            $this->isQtyLessThanZero($goods);
            $this->isQtyLessThanTotalUsedItems($goods, $request);
            if ($goods->type === 'aset') {
                $this->accountTransactionService->createDebitTransaction($description, $amount, $request->asset_account, null);
                $this->accountTransactionService->createCreditTransaction($description, $amount, null, $goods->account_id);
            }
            UsedItems::create([
                'account_id' => $goods->account_id,
                'goods_id' => $goods->id,
                'total_used' => $request->total_used,
                'created_by' => $request->user()->id,
            ]);
        });
    }

    private function isQtyGreaterThanZero(Goods $goods, Request $request): void
    {
        if ($goods->qty > 0) {
            $goods->qty -= $request->total_used;
            $goods->save();
        }
    }

    /**
     * @throws \Exception
     */
    public function isQtyLessThanZero(Goods $goods): void
    {
        if ($goods->qty < 0) {
            throw new \Exception('Stok sudah habis');
        }
    }

    /**
     * @throws \Exception
     */
    public function isQtyLessThanTotalUsedItems(Goods $goods, Request $request): void
    {
        if ($goods->qty < $request->total_used) {
            throw new \Exception('Stok tidak mencukupi');
        }
    }
}
