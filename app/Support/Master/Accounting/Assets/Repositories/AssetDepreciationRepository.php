<?php

namespace App\Support\Master\Accounting\Assets\Repositories;

use AllowDynamicProperties;
use App\Models\AssetDepreciation;

#[AllowDynamicProperties] class AssetDepreciationRepository
{
    public function __construct()
    {
        $this->assetDepreciation = new AssetDepreciation();
    }


    public function getSumDepreciationAmount(int $assetId, string $startDate, string $endDate)
    {
        return $this->assetDepreciation->query()
            ->where('asset_id', $assetId)
            ->whereBetween('depreciation_date', [$startDate, $endDate])
            ->sum('depreciation_amount');
    }
}
