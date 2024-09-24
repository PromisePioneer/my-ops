<?php

namespace App\Service\Assets;

use App\Models\Asset;
use App\Models\AssetDepreciation;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class AssetDepreciationService
{
    private static int $perPage = 10;

    public function data(Asset $asset): LengthAwarePaginator
    {
        $data = AssetDepreciation::with('asset')
            ->where('asset_id', $asset->id)
            ->paginate(10);
        return self::formattedData($data);
    }


    public function formattedData(LengthAwarePaginator $assetData): LengthAwarePaginator
    {
        $data = $assetData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => Carbon::parse($item->depreciation_date)->translatedFormat('j F Y'),
                'amount' => number_format($item->depreciation_amount, 2),
            ];
        });

        $assetData->setCollection($data);
        return $assetData;
    }


}