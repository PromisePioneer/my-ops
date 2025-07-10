<?php

namespace App\Support\Transactions\Repositories;

use AllowDynamicProperties;
use App\Enum\Transaction\TransactionType;
use App\Models\Master\Common\Branch;
use App\Models\Transaction;
use App\Support\Master\Common\Branch\Repository\BranchRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class TransactionRepository
{
    public function __construct()
    {
        $this->transaction = new Transaction();
        $this->branchRepository = new BranchRepository();
    }


    public function getTransactions(): Builder
    {
        return $this->transaction->with([
            'branch',
            'item.unitType',
            'debitAccount',
            'creditAccount'
        ])->where('type', '!=', TransactionType::INITIAL_INVENTORY_BALANCE->value);
    }

    public function getInitialInventoryBalance(Request $request): Builder
    {
        return $this->transaction->with(['branch', 'item.unitType', 'stockAccount'])
            ->where('type', TransactionType::INITIAL_INVENTORY_BALANCE->value)
            ->when(!empty($request->user()->branch_id), function (Builder $query) use ($request) {
                $branch = $this->branchRepository->findById($request->user()->branch_id)->children->pluck('id')->toArray();
                $query->whereIn('branch_id', $branch);
            })->orderBy('created_at');
    }

}
