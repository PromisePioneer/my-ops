<?php

namespace App\Support\Inventory;

use App\Http\Requests\Inventory\GoodsRequest;
use App\Models\Account;
use App\Models\ItemCollection;
use App\Models\Master\Common\UnitType;
use App\Models\SubAccount;
use App\Support\AccountTransactions\AccountTransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoodsServices
{
    public const string INSERT_ACCOUNT_TRANSACTION_DESCRIPTION = 'Pembelian Barang %s, %s %s senilai Rp.%s';

    private AccountTransactionService $accountTransactionService;

    private UnitType $unitType;


    public function __construct()
    {
        $this->accountTransactionService = new AccountTransactionService();
        $this->unitType = new UnitType();
    }

    public function store(GoodsRequest $request): void
    {
        $data = $request->validated();
        $data['file'] = self::handleFileUpload($request);
        $data['created_by'] = $request->user()->id;
        ItemCollection::create($data);
    }

    private static function handleFileUpload(GoodsRequest $request, ?ItemCollection $goods = null): string
    {
        if ($goods) {
            Storage::delete($goods->file);
        }

        return $request->file('file')->store('goods/image', 'public');
    }

    public function update(GoodsRequest $request, ItemCollection $goods): void
    {
        $data = $request->validated();
        $data['file'] = self::handleFileUpload($request, $goods);
        $goods->update($data);
    }

    public function confirm($goods): void
    {
        $unitType = $this->unitType->getSelectedData($goods->unit_type_id);
        $description = sprintf(
            self::INSERT_ACCOUNT_TRANSACTION_DESCRIPTION,
            $goods->name,
            $goods->qty,
            $unitType['name'],
            number_format($goods->total_price)
        );
//        $kasAccount = $this->subAccount->getKasSubAccount();

//        DB::transaction(function () use ($goods, $description, $kasAccount) {
//            $this->accountTransactionService->createDebitTransaction(
//                $description,
//                $goods->total_price,
//                $goods->account_id
//            );
//            $this->accountTransactionService->createCreditTransaction(
//                $description,
//                $goods->total_price,
//                $kasAccount->id
//            );
            $goods->confirmation_status = 1;
            $goods->save();
//        });
    }


    public function getPersediaanAccount(Request $request)
    {
        $search = $request->input('search');
        $account = Account::with('parent')->whereHas('parent', function ($query) use ($search) {
            $query->where('code', 112);
        });


        if (!empty($search)) {
            $account->where('name', 'like', '%'.$search.'%');
        }

        $data = $account->get();
        return $data->map(function ($account) {
            return [
                'text' => $account->name,
                'id' => $account->id,
            ];
        });
    }
}
