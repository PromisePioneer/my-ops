<?php

namespace App\Support\Inventory\Stock\StockWithdrawal\Repository;

use App\Models\StockWithdrawal;
use Illuminate\Database\Eloquent\Builder;

class StockWithdrawalServiceRepository
{
    public function getStockWithDrawalQuery(): Builder
    {
        return StockWithdrawal::with('branch', 'stocker', 'kca', 'stockWithdrawalByEmployee');
    }
}
