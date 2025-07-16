<?php

namespace App\Support\Master\Accounting\Assets\Service;

use AllowDynamicProperties;
use App\Models\Asset;
use App\Support\Master\Accounting\Assets\Repositories\AssetDepreciationRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use function App\Helper\currencyFormat;

#[AllowDynamicProperties] class AssetDepreciationService
{
    public function __construct()
    {
        $this->assetDepreciationRepository = new AssetDepreciationRepository();
    }

    public function data(Asset $asset): Collection
    {
        $depreciation = $this->assetDepreciationRepository->findByAssetId($asset->id)->get();
        return self::formattedData($depreciation, $asset);
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
