<?php

namespace App\Http\Controllers;

use App\Models\ItemCatalog;
use App\Models\ItemCollection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMutationController extends Controller
{
    public function __construct()
    {

    }

    public function create(ItemCollection $itemCollection): View
    {
        return view('pages.inventory.stock-mutation.form', compact('itemCollection'));
    }


}
