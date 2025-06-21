<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Support\Inventory\StockMutationAndWithdrawalRecord\StockMutationAndWithdrawalRecordService;
use Illuminate\View\View;

#[AllowDynamicProperties] class StockMutationAndWithdrawalRecordController extends Controller
{
    public function __construct()
    {
        $this->stockMutationAndWithdrawalRecordService = new StockMutationAndWithdrawalRecordService();
    }


    public function index(ItemCatalog $itemCatalog, Stock $stock): View
    {
        return view('pages.inventory.stock-mutation-and-withdrawal-record.index', compact($itemCatalog, $stock));
    }
}
