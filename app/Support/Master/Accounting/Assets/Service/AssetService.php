<?php

namespace App\Support\Master\Accounting\Assets\Service;

use AllowDynamicProperties;
use App\Http\Requests\AssetRequest;
use App\Models\Account;
use App\Models\Asset;
use App\Models\AssetDepreciation;
use App\Support\AccountTransactions\AccountTransactionService;
use App\Support\Master\Accounting\Assets\Repositories\AssetRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class AssetService
{

    private const string PURCHASE_ASSET_DESCRIPTION = 'Pembelian %s unit %s';
    private const string DEPRECIATION_ASSET_DESCRIPTION = 'Penyusutan %s unit %s';

    private static int $perPage = 10;

    public function __construct()
    {
        $this->accountTransactionService = new AccountTransactionService();
        $this->assetRepository = new AssetRepository();
    }

    public function data(): LengthAwarePaginator
    {
        $data = $this->assetRepository->data()->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $assetData): LengthAwarePaginator
    {
        $data = $assetData->getCollection()->map(function ($item) {

            return [
                'id' => $item->id,
                'branch_name' => $item->branch->name . ' ' . $item->branch->parent->name ?? null,
                'name' => $item->name,
                'debit_account' => $item->debitAccount?->name,
                'unit' => $item->unit,
                'useful_life' => $item->useful_life,
                'price_per_unit' => 'Rp.' . number_format($item->price_per_unit, 2, '.', '.'),
                'price_at_first_recieved' => number_format($item->price_at_first_recieved, 2, '.', '.'),
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
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('account', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('code', 'like', '%' . $search . '%');
                    })->orWhere('useful_life', 'like', '%' . $search . '%');
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

        $unitPriceformattedValue = str_replace('.', '', $request->input('price_per_unit'));
        $unitPriceformattedValue = str_replace(',', '.', $unitPriceformattedValue);
        $unitPrice = (float)$unitPriceformattedValue;

        $data['price_per_unit'] = $unitPrice;
        $data['total_price'] = $unitPrice * $data['unit'];
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
            $this->depreciation($request, $asset);
            $asset->status = 1;
            $asset->save();
            $this->accountTransactionService->createDebitTransaction(
                $asset->branch_id,
                $description,
                $asset->debit_account_id,
                $asset->total_price,
            );
        });
    }


    public function depreciation(Request $request, Asset $asset): void
    {
        $yearsStart = Carbon::parse($request->date_received);
        $yearsEnd = Carbon::parse($request->date_received)->addYears($asset->useful_life);
        $diffInMonth = $yearsStart->diffInMonths($yearsEnd);


        $residu = $asset->total_price / $diffInMonth;
        $depreciation = ($asset->total_price - $residu) / $diffInMonth;
        $price = $asset->total_price;

        for ($i = 1; $i <= $diffInMonth; $i++) {
            $date = Carbon::parse($asset->date_received)->addMonths($i);
            $price -= $depreciation;

            DB::transaction(function () use ($date, $price, $asset, $i) {
                AssetDepreciation::create([
                    'asset_id' => $asset->id,
                    'depreciation_date' => $date,
                    'depreciation_amount' => $price,
                ]);

                $accounts = Account::where('code', '130')->first();
                $description = sprintf(self::DEPRECIATION_ASSET_DESCRIPTION, $asset->unit, $asset->name, $i);

                $this->accountTransactionService->createCreditTransaction(
                    $asset->branch_id,
                    $description,
                    $accounts->id,
                    $price,
                    null,
                    $date
                );
            });
        }
    }

    public function update(AssetRequest $request, Asset $asset): bool
    {
        $data = $request->validated();
        $data['price_at_first_recieved'] = $data['price_per_unit'] * $data['unit'];
        return $asset->update($data);
    }


    public function depreciationData(Asset $asset)
    {
        return AssetDepreciation::with('asset')->where('asset_id', $asset->id)->get()->map(function ($query) {
            return [
                'id' => $query->id,
                'depreciation_date' => formatDate($query->depreciation_date),
                'depreciation_amount' => 'Rp.' . number_format($query->depreciation_amount, 2, '.', '.'),
            ];
        });
    }
}
