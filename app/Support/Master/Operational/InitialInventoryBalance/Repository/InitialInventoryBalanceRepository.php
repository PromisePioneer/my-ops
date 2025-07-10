<?php

namespace App\Support\Master\Operational\InitialInventoryBalance\Repository;

use App\Enum\Transaction\TransactionType;
use App\Models\InitialInventoryBalance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InitialInventoryBalanceRepository
{
    public function data(): Builder
    {
        return InitialInventoryBalance::with('branch', 'supplier', 'item', 'branch.parent', 'stockAccount');
    }


    public function search(Request $request, $query, $branch): Builder
    {

        if (!empty($request->user()->branch_id)) {
            $query->whereIn('branch_id', $branch);
        }

        return $query->where('transactions.type', TransactionType::INITIAL_INVENTORY_BALANCE->value)
            ->join('branches', 'transactions.branch_id', 'branches.id')
            ->join('branches as parent_branches', 'parent_branches.id', '=', 'branches.parent_id')
            ->join('contacts', 'transactions.contact_id', '=', 'contacts.id')
            ->join('item_collections', 'transactions.item_id', '=', 'item_collections.id')
            ->select('transactions.*', 'parent_branches.name', 'item_collections.name');
    }
}
