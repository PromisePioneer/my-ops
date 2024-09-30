<?php

namespace App\Service\Master;

use App\Http\Requests\AssetRequest;
use App\Models\Asset;
use App\Models\AssetDepreciation;
use App\Service\Accounts\AccountTransactionService;
use Carbon\Carbon;
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
        $data = Asset::with('branch', 'debitAccount', 'creditAccount')->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $assetData): LengthAwarePaginator
    {
        $data = $assetData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_name' => $item->branch->name ?? null,
                'name' => $item->name,
                'debit_account' => $item->debitAccount->name,
                'credit_account' => $item->creditAccount->name,
                'unit' => $item->unit,
                'useful_life' => $item->useful_life,
                'price_per_unit' => number_format($item->price_per_unit, 2),
                'price_at_first_recieved' => number_format($item->price_at_first_recieved, 2),
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
        $data['total_price'] = $data['price_per_unit'] * $data['unit'];
        $data['residu'] = $data['total_price'] / $data['useful_life'];
        Asset::create($data);
    }

    /**
     * @throws Throwable
     */
    public function confirm(Request $request, Asset $asset): void
    {
        $description = sprintf(self::PURCHASE_ASSET_DESCRIPTION, $asset->unit, $asset->name);
        DB::transaction(function () use ($request, $description, $asset) {
            $residu = $asset->total_price / $asset->useful_life;
            $depreciation = ($asset->total_price - $residu) / $asset->useful_life;
            $price = $asset->total_price;

            for ($i = 1; $i <= $asset->useful_life; $i++) {
                $date = Carbon::parse($asset->date_received)->addYear($i);
                $price -= $depreciation;

                AssetDepreciation::create([
                    'asset_id' => $asset->id,
                    'depreciation_date' => $date,
                    'depreciation_amount' => $price,
                ]);
            }

            $asset->status = 1;
            $asset->save();
            $this->accountTransactionService->createDebitTransaction(
                $request,
                $description,
                $asset->total_price,
                $asset->debit_account_id
            );
            $this->accountTransactionService->createCreditTransaction(
                $request,
                $description,
                $asset->total_price,
                $asset->credit_account_id
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