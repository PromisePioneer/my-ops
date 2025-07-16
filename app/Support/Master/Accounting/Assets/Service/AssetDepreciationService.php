<?php

namespace App\Support\Master\Accounting\Assets\Service;

use App\Models\Asset;
use App\Models\AssetDepreciation;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use function App\Helper\currencyFormat;

class AssetDepreciationService
{
    private static int $perPage = 10;

    public function data(Asset $asset): Collection
    {
        $depreciations = AssetDepreciation::with('asset')
            ->where('asset_id', $asset->id)
            ->get();
        return self::formattedData($depreciations, $asset);
    }


    public function formattedData(Collection $depreciation, Asset $asset): Collection
    {
        $assetTotalPrice = $asset->price;
        return $depreciation->map(function ($item) use (&$assetTotalPrice) {
            $assetTotalPrice -= $item->depreciation_amount;
            return [
                'id' => $item->id,
                'date' => Carbon::parse($item->depreciation_date)->translatedFormat('j F Y'),
                'amount' => currencyFormat($assetTotalPrice),
            ];
        });
    }


}
