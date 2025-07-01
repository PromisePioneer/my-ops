<?php

namespace App\Support\Master\Operational\InitialInventoryBalance\Service;

use App\Models\Master\Common\Branch;
use Illuminate\Http\Request;

class InitialInventoryBalanceACLFilter
{
    public static function apply($query, Request $request)
    {
        if (!empty($request->user()->branch_id)) {
            $branch = Branch::with('children')
                ->find($request->user()->branch_id)
                ->children
                ->pluck('id')
                ->toArray();

            $query->whereIn('branch_id', $branch);
        }

        return $query;
    }
}
