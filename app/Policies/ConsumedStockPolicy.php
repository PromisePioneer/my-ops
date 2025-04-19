<?php

namespace App\Policies;

use App\Models\ConsumedStock;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConsumedStockPolicy
{
    public function create(User $user): bool
    {
        return $user->can('Input Pemakaian Stok Barang');
    }
}
