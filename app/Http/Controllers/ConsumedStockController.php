<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\UsedStockRequest;
use App\Models\Account;
use App\Models\Asset;
use App\Models\ConsumedStock;
use App\Models\ItemCollection;
use App\Models\Stock;
use App\Models\Transaction;
use App\Support\AccountTransactions\AccountTransactionService;
use App\Support\Inventory\Stock\ConsumedStockService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class ConsumedStockController extends Controller
{
    private const string ACCOUNT_TRANSACTION_DETAIL = 'Pemakaian %s %s %s';


    public function __construct()
    {
        $this->consumedStockService = new ConsumedStockService();
    }

    public function index(): View
    {
        return view('used_stock.index');
    }


    public function assetAccount(Request $request)
    {
        $search = $request->input('search');
        $account = Account::whereBetween('code', ['121', '126']);

        if ($search !== '') {
            $account->whereBetween('code', ['121', '126'])
                ->where('name', 'like', '%' . $search . '%');
        }

        return $account->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }

    /**
     * @throws Throwable
     */
    public function store(UsedStockRequest $request, AccountTransactionService $accountTransactionService): JsonResponse
    {
        $this->authorize('create', ConsumedStock::class);
        $this->consumedStockService->store($request);
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

}
