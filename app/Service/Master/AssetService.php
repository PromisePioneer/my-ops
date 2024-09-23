<?php

namespace App\Service\Master;

use App\Http\Requests\AssetRequest;
use App\Models\Account;
use App\Models\Asset;
use App\Service\Accounts\AccountTransactionService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

class AssetService
{

    private const string PURCHASE_ASSET_DESCRIPTION = 'Pembelian %s unit %s';

    private static int $perPage = 10;
    private AccountTransactionService $accountTransactionService;

    public function __construct()
    {
        $this->accountTransactionService = new AccountTransactionService();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $data = Asset::with('branch', 'account')->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $assetData): LengthAwarePaginator
    {
        $data = $assetData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_name' => $item->branch->name ?? null,
                'name' => $item->name,
                'account_name' => $item->account->name,
                'unit' => $item->unit,
                'useful_life' => $item->useful_life,
                'price_per_unit' => number_format($item->price_per_unit, 2),
                'price_at_first_recieved' => number_format($item->price_at_first_recieved, 2),
                'depreciation_rate' => number_format($item->depreciation_rate, 2),
                'status' => $item->status,
            ];
        });

        $assetData->setCollection($data);
        return $assetData;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = Asset::with('branch', 'account');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhereHas('account', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%')
                            ->orWhere('code', 'like', '%'.$search.'%');
                    })->orWhere('useful_life', 'like', '%'.$search.'%');
            });
        }


        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    /**
     * @throws Throwable
     */
    public function store(AssetRequest $request): void
    {
        $data = $request->validated();
        $data['price_at_first_recieved'] = $data['price_per_unit'] * $data['unit'];
        Asset::create($data);
    }

    /**
     * @throws Throwable
     */
    public function confirm(Asset $asset): void
    {
        $creditAccount = Account::where('code', '111')->first();
        $description = sprintf(self::PURCHASE_ASSET_DESCRIPTION, $asset->unit, $asset->name);
        DB::transaction(function () use ($description, $asset, $creditAccount) {
            $asset->status = 1;
            $asset->save();
            $this->accountTransactionService->createDebitTransaction(
                $description,
                $asset->price_at_first_recieved,
                $asset->account_id
            );
            $this->accountTransactionService->createCreditTransaction(
                $description,
                $asset->price_at_first_recieved,
                $creditAccount->id
            );
        });
    }

    public function update(AssetRequest $request, Asset $asset): bool
    {
        $data = $request->validated();
        $data['price_at_first_recieved'] = $data['price_per_unit'] * $data['unit'];
        return $asset->update($data);
    }
}