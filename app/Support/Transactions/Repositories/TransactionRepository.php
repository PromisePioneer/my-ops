<?php

namespace App\Support\Transactions\Repositories;

use App\Enum\Transaction\TransactionType;
use App\Models\Master\Common\Branch;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TransactionRepository
{
    public function getTransactions(): Builder
    {
        return Transaction::with('branch', 'item.unitType', 'debitAccount', 'creditAccount')
            ->where('type', '!=', TransactionType::INITIAL_INVENTORY_BALANCE->value);;
    }

    public function getInitialInventoryBalance(Request $request): Builder
    {
        return Transaction::with(['branch', 'item.unitType', 'stockAccount'])
            ->where('type', TransactionType::INITIAL_INVENTORY_BALANCE->value)
            ->when(!empty($request->user()->branch_id), function (Builder $query) use ($request) {
                $branch = Branch::with('children')
                    ->find($request->user()->branch_id)
                    ->children->pluck('id')->toArray();
                $query->whereIn('branch_id', $branch);
            });
    }


    public function searchInitialInventoryBalance($query, string $search): Builder
    {
        $this->getInitialInventoryBalance()->whereHas('branch', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orWhereHas('supplier', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orWhereHas('item', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }
}
