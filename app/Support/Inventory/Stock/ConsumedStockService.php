<?php

namespace App\Support\Inventory\Stock;

use AllowDynamicProperties;
use App\Http\Requests\UsedStockRequest;
use App\Models\Account;
use App\Models\Asset;
use App\Models\ConsumedStock;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Models\Transaction;
use App\Support\AccountTransactions\AccountTransactionService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class ConsumedStockService
{

    private const string ACCOUNT_TRANSACTION_DETAIL = 'Pemakaian %s %s %s';
    private static int $perPage = 10;

    public function __construct()
    {
        $this->accountTransactionService = new AccountTransactionService();
    }


    public function data(): LengthAwarePaginator
    {
        $consumedStock = ConsumedStock::with('stock.item', 'branch', 'submittedBy')->paginate(self::$perPage);
        return self::formattedData($consumedStock);
    }


    public function formattedData(LengthAwarePaginator $consumedStock): LengthAwarePaginator
    {
        $data = $consumedStock->getCollection()->map(callback: function ($item) {
            $date = Carbon::parse($item->created_at)->locale('id');
            $date->settings(['formatFunction' => 'translatedFormat']);


            return [
                'id' => $item->id,
                'date' => $date->format('l, j F Y h:i A'),
                'branch_name' => $item->branch->name . ' - ' . $item->branch->parent->name,
                'item_name' => $item->stock->item->name ?? null,
                'qty' => $item->qty . ' ' . $item->stock->item->unitType->name,
                'submitted_by' => $item->submittedBy->name,
            ];
        });

        $consumedStock->setCollection($data);
        return $consumedStock;
    }


    /**
     * @throws Throwable
     */
    public function store(UsedStockRequest $request)
    {
        DB::transaction(function () use ($request) {
            $stock = Stock::where('branch_id', $request->branch_id)
                ->where('item_id', $request->goods_id)
                ->first();

            $transaction = Transaction::where('id', $stock->transaction_id)->firstOrFail();
            $goods = ItemCollection::with('category', 'unitType')
                ->where('id', $request->input('goods_id'))
                ->first();

            if ($goods->category->name === 'JUAL') {
                $this->ifSellItem($request, $goods, $stock, $transaction);
            }

            if ($goods->category->name === 'ASET') {
                $this->ifAsetItem($request, $goods, $stock, $transaction);
            }
        });
    }


    public function ifAsetItem($request, $goods, $stock, $transaction): void
    {
        if ($goods->category->name === 'ASET') {
            $asset = Asset::where('branch_id', $request->input('branch_id'))->where('name', $goods->name)->first();
            if ($asset) {
                $asset->increment('unit', $request->qty);
            } else {
                $usefulLife = $this->usefulLife(Account::where('id', $goods->asset_account_id)->first()->code, $goods->material);
                $branch = Branch::with('parent')->where('id', $request->input('branch_id'))->first();
                Asset::create([
                    'branch_id' => $request->input('branch_id'),
                    'debit_account_id' => $goods->asset_account_id,
                    'credit_account_id' => $transaction->debit_account_id,
                    'date_received' => Carbon::now(),
                    'name' => $goods->name,
                    'price_per_unit' => $stock->transaction->unit_price,
                    'unit' => $request->qty,
                    'total_price' => $stock->transaction->total_price,
                    'useful_life' => $usefulLife
                ]);


                $stock->decrement('qty', $request->qty);


                ConsumedStock::create([
                    'branch_id' => empty($request->user()->branch_id)
                        ? $request->input('branch_id')
                        : $request->user()->branch_id,
                    'stock_id' => $stock->id,
                    'debit_account_id' => $goods->asset_account_id,
                    'credit_account_id' => $transaction->debit_account_id,
                    'qty' => $request->qty,
                    'submitted_by' => $request->user()->id,
                ]);


                $this->accountTransactionService->createDebitTransaction(
                    $branch->parent->id,
                    sprintf(self::ACCOUNT_TRANSACTION_DETAIL, $goods->name, $request->qty, $goods->unitType->name),
                    $goods->asset_account_id,
                    $transaction->unit_price * $request->qty,
                    $transaction->id,
                );

                $this->accountTransactionService->createCreditTransaction(
                    $branch->parent->id,
                    sprintf(self::ACCOUNT_TRANSACTION_DETAIL, $goods->name, $request->qty, $goods->unitType->name),
                    $transaction->debit_account_id,
                    $transaction->unit_price * $request->qty,
                    $transaction->id
                );
            }

        }
    }


    public function ifSellItem($request, $goods, $stock, $transaction): void
    {
        $debitAccount = Account::where('code', '501-01')->first();
        $branch = Branch::with('parent')->where('id', $request->input('branch_id'))->first();

        ConsumedStock::create([
            'branch_id' => empty($request->user()->branch_id)
                ? $request->input('branch_id')
                : $request->user()->branch_id,
            'stock_id' => $stock->id,
            'debit_account_id' => $debitAccount->id,
            'credit_account_id' => $transaction->debit_account_id,
            'qty' => $request->qty,
            'submitted_by' => $request->user()->id,
        ]);


        $stock->decrement('qty', $request->qty);

        $this->accountTransactionService->createDebitTransaction(
            $branch->parent->id,
            sprintf(self::ACCOUNT_TRANSACTION_DETAIL, $goods->name, $request->qty, $goods->unitType->name),
            $debitAccount->id,
            $transaction->unit_price * $request->qty,
            $transaction->id,
        );

        $this->accountTransactionService->createCreditTransaction(
            $branch->parent->id,
            sprintf(self::ACCOUNT_TRANSACTION_DETAIL, $goods->name, $request->qty, $goods->unitType->name),
            $transaction->debit_account_id,
            $transaction->unit_price * $request->qty,
            $transaction->id
        );
    }


    public function usefulLife($code, $goodsMaterial): ?int
    {

        if ($code === '121') {
            return null;
        }

        if ($code === '122') {
            return 20;
        }

        if ($code === '123' || $code === '124') {
            return 8;
        }

        if ($code === '125' && $goodsMaterial === 'Besi' || $code === '126' && $goodsMaterial === 'Besi') {
            return 8;
        }

        if ($code === '125' && $goodsMaterial === 'Non Besi' || $code === '126' && $goodsMaterial === 'Non Besi') {
            return 4;
        }


        return null;
    }
}
