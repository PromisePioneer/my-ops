<?php

namespace App\Support\Master\Accounting\Assets\Repositories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Builder;

class AssetRepository
{
    public function data(): Builder
    {
        return Asset::with('branch', 'debitAccount', 'creditAccount');
    }
}
