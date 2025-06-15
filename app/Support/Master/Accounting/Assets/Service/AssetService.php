<?php

namespace App\Support\Master\Accounting\Assets\Service;

use AllowDynamicProperties;
use App\Http\Requests\AssetRequest;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Asset;
use App\Models\AssetDepreciation;
use App\Models\ItemCollection;
use App\Support\AccountTransactions\AccountTransactionService;
use App\Support\HelperService\UsefulLifeService;
use App\Support\Master\Accounting\Assets\Repositories\AssetRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\currencyFormat;

#[AllowDynamicProperties] class AssetService
{

    private const string PURCHASE_ASSET_DESCRIPTION = 'Pembelian  %s';
    private const string DEPRECIATION_ASSET_DESCRIPTION = 'Penyusutan %s';
    private const string INITIAL_BALANCE_ASSET_DESCRIPTION = 'Saldo Awal Aset %s';

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
                'branch_name' => $item->branch?->name . ' ' . $item->branch?->parent?->name ?? null,
                'code' => $item->code,
                'name' => $item->item->name,
                'debit_account' => $item->item->assetAccount->name,
                'useful_life' => $item->useful_life,
                'price' => currencyFormat($item->price),
                'status' => $item->status,
            ];
        });

        $assetData->setCollection($data);
        return $assetData;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = Asset::with('branch');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('item', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })->orWhere('useful_life', 'like', '%' . $search . '%');
            });
        }


        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->assetRepository->data();
        $filter = AssetQueryFilterService::apply($query, $request);
        return self::formattedData($filter->paginate(self::$perPage));
    }


    /**
     * @throws Throwable
     */
    public function store(AssetRequest $request): void
    {
        $unitPriceformattedValue = str_replace('.', '', $request->input('price'));
        $unitPriceformattedValue = str_replace(',', '.', $unitPriceformattedValue);
        $unitPrice = (float)$unitPriceformattedValue;


        $itemCollection = ItemCollection::find($request->item_id);
        $assetAccount = Account::find($itemCollection->asset_account_id);


        Asset::create([
            'branch_id' => $request->branch_id,
            'code' => $request->code,
            'date' => $request->date,
            'item_id' => $request->item_id,
            'useful_life' => UsefulLifeService::getUsefulLife($assetAccount->code, $itemCollection->non_building_group, $itemCollection->building_type),
            'price' => $unitPrice,
        ]);
    }


    public function update(AssetRequest $request, Asset $asset): void
    {
        $unitPriceformattedValue = str_replace('.', '', $request->input('price'));
        $unitPriceformattedValue = str_replace(',', '.', $unitPriceformattedValue);
        $unitPrice = (float)$unitPriceformattedValue;


        $itemCollection = ItemCollection::find($request->item_id);
        $assetAccount = Account::find($itemCollection->asset_account_id);

        $asset->update([
            'branch_id' => $request->branch_id,
            'code' => $request->code,
            'date' => $request->date,
            'item_id' => $request->item_id,
            'useful_life' => UsefulLifeService::getUsefulLife($assetAccount->code, $itemCollection->non_building_group, $itemCollection->building_type),
            'price' => $unitPrice,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function confirm(Asset $asset): void
    {
        $asset->load('item', 'branch');
        $description = sprintf(self::PURCHASE_ASSET_DESCRIPTION, $asset->name);
        DB::transaction(function () use ($description, $asset) {
            $this->depreciation($asset);
            $asset->status = 1;
            $asset->save();
        });


        if (empty($asset->stock_id)) {
            AccountTransaction::create([
                'branch_id' => $asset->branch->parent_id,
                'date' => $asset->date,
                'account_id' => $asset->item->asset_account_id,
                'description' => sprintf(self::INITIAL_BALANCE_ASSET_DESCRIPTION, $asset->item->name),
                'transaction_type' => 'SA',
                'entries_type' => 'Debit',
                'amount' => $asset->price,
            ]);
        } else {
            $this->accountTransactionService->createDebitTransaction(
                $asset->branch->parent_id,
                $description,
                $asset->item->asset_account_id,
                $asset->price,
            );
        }
    }


    /**
     * @throws Throwable
     */
    public function depreciation(Asset $asset): void
    {
        $yearsStart = Carbon::parse($asset->date)->startOfMonth();
        $yearsEnd = Carbon::parse($asset->date)->startOfMonth()->addYears($asset->useful_life);
        $diffInMonth = $yearsStart->diffInMonths($yearsEnd);

        $depreciation = ($asset->price) / $diffInMonth;
        $price = $asset->price;

        $asset->update([
            'depreciation' => ($asset->price) / (int)$yearsStart->diffInYears($yearsEnd),
        ]);

        for ($i = 0; $i < $diffInMonth; $i++) {
            $date = Carbon::parse($asset->date)->startOfMonth()->addMonths($i);
            if ($i === 0) {
                $price;
            } else {
                $price -= $depreciation;
            }

            DB::transaction(function () use ($date, $price, $asset, $i, $depreciation) {
                AssetDepreciation::create([
                    'asset_id' => $asset->id,
                    'depreciation_date' => $date,
                    'depreciation_amount' => $depreciation,
                ]);

                $account = Account::where('code', '130')->first();
                $description = sprintf(self::DEPRECIATION_ASSET_DESCRIPTION, $asset->name, $i);

                $this->accountTransactionService->createCreditTransaction(
                    $asset->branch_id,
                    $description,
                    $account->id,
                    $depreciation,
                    null,
                    $date
                );
            });
        }
    }

}
