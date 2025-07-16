<?php

namespace App\Support\Master\Accounting\Assets\Repositories;

use AllowDynamicProperties;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class AssetRepository
{


    public function __construct()
    {
        $this->asset = new Asset();
    }
    public function data(): Builder
    {
        return Asset::with(['branch', 'branch.parent', 'item'])->orderBy('date', 'ASC');
    }

    public function findById(int $assetId): Asset
    {
        return $this->asset->query()->find($assetId);
    }
}
