<?php

namespace App\Support\Transactions\Repositories;

use AllowDynamicProperties;
use App\Enum\Transaction\TransactionType;
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


    public function getTransactions(Request $request): Builder
    {
        return $this->transaction->with([
            'company',
            'branch',
            'item.unitType',
            'debitAccount',
            'creditAccount'
        ])->where('company_id', $request->session()->get('company_session'))
            ->where('type', '!=', TransactionType::INITIAL_INVENTORY_BALANCE->value);
    }

    public function getInitialInventoryBalance(Request $request): Builder
    {
        return $this->transaction->with(['branch', 'item.unitType', 'stockAccount'])
            ->where('company_id', $request->session()->get('company_session'))
            ->where('type', TransactionType::INITIAL_INVENTORY_BALANCE->value)
            ->when(!empty($request->user()->branch_id), function (Builder $query) use ($request) {
                $branch = $this->branchRepository->findById($request->user()->branch_id)->children->pluck('id')->toArray();
                $query->whereIn('branch_id', $branch);
            })->orderBy('created_at');
    }

    public function searchQuery(Request $request, $query)
    {
        return $query->leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
            ->where('transactions.company_id', $request->session()->get('company_session'))
            ->leftJoin('item_collections', 'transactions.item_id', '=', 'item_collections.id')
            ->where('transactions.type', '!=', TransactionType::INITIAL_INVENTORY_BALANCE->value)
            ->select('transactions.*', 'item_collections.name as collection_name');
    }

}
