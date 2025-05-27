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

    private const string PURCHASE_ASSET_DESCRIPTION = 'Pembelian %s unit %s';
    private const string DEPRECIATION_ASSET_DESCRIPTION = 'Penyusutan %s unit %s';
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
                'unit' => $item->unit,
                'useful_life' => $item->useful_life,
                'price_per_unit' => currencyFormat($item->price_per_unit, 2, '.', '.'),
                'price_at_first_recieved' => currencyFormat($item->price_at_first_recieved, 2, '.', '.'),
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
        $unitPriceformattedValue = str_replace('.', '', $request->input('price_per_unit'));
        $unitPriceformattedValue = str_replace(',', '.', $unitPriceformattedValue);
        $unitPrice = (float)$unitPriceformattedValue;


        $itemCollection = ItemCollection::find($request->item_id);
        $assetAccount = Account::find($itemCollection->asset_account_id);


        Asset::create([
            'branch_id' => $request->branch_id,
            'code' => $request->code,
            'date_received' => $request->date_received,
            'item_id' => $request->item_id,
            'unit' => 1,
            'useful_life' => UsefulLifeService::getUsefulLife($assetAccount->code, $itemCollection->non_building_group, $itemCollection->building_type),
            'price_per_unit' => $unitPrice,
            'total_price' => $unitPrice,
        ]);

        $this->accountTransactionService->createDebitTransaction(
            $request->branch_id,
            sprintf(self::INITIAL_BALANCE_ASSET_DESCRIPTION, $itemCollection->name),
            $itemCollection->asset_account_id,
            $unitPrice,
        );
    }

    /**
     * @throws Throwable
     */
    public function confirm(Request $request, Asset $asset): void
    {
        $asset->load('item', 'branch');
        $description = sprintf(self::PURCHASE_ASSET_DESCRIPTION, $asset->unit, $asset->name);
        DB::transaction(function () use ($request, $description, $asset) {
            $this->depreciation($request, $asset);
            $asset->status = 1;
            $asset->save();
        });


        if (empty($asset->stock_id)) {
            AccountTransaction::create([
                'branch_id' => $asset->branch->parent_id,
                'date' => $asset->date_received,
                'account_id' => $asset->item->asset_account_id,
                'description' => sprintf(self::INITIAL_BALANCE_ASSET_DESCRIPTION, $asset->item->name),
                'transaction_type' => 'SA',
                'entries_type' => 'Debit',
                'amount' => $asset->total_price,
            ]);
        } else {
            $this->accountTransactionService->createDebitTransaction(
                $asset->branch->parent_id,
                $description,
                $asset->item->asset_account_id,
                $asset->total_price,
            );
        }
    }


    /**
     * @throws Throwable
     */
    public function depreciation(Request $request, Asset $asset): void
    {
        $yearsStart = Carbon::parse($request->date_received)->startOfMonth();
        $yearsEnd = Carbon::parse($request->date_received)->startOfMonth()->addYears($asset->useful_life);
        $diffInMonth = $yearsStart->diffInMonths($yearsEnd);
        $depreciation = ($asset->total_price) / $diffInMonth;
        $price = $asset->total_price;

        $asset->update([
            'depreciation' => ($asset->total_price) / (int)$yearsStart->diffInYears($yearsEnd),
        ]);

        for ($i = 0; $i < $diffInMonth; $i++) {
            $date = Carbon::parse($asset->date_received)->startOfMonth()->addMonths($i);
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
                $description = sprintf(self::DEPRECIATION_ASSET_DESCRIPTION, $asset->unit, $asset->name, $i);

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

    public function update(AssetRequest $request, Asset $asset): bool
    {
        $unitPriceformattedValue = str_replace('.', '', $request->input('price_per_unit'));
        $unitPriceformattedValue = str_replace(',', '.', $unitPriceformattedValue);
        $unitPrice = (float)$unitPriceformattedValue;


        $itemCollection = ItemCollection::find($request->item_id);
        $assetAccount = Account::find($itemCollection->asset_account_id);

        return $asset->update([
            'branch_id' => $request->branch_id,
            'code' => $request->code,
            'date_received' => $request->date_received,
            'item_id' => $request->item_id,
            'unit' => 1,
            'useful_life' => UsefulLifeService::getUsefulLife($assetAccount->code, $itemCollection->non_building_group, $itemCollection->building_type),
            'price_per_unit' => $unitPrice,
            'total_price' => $unitPrice,
        ]);
    }

}
