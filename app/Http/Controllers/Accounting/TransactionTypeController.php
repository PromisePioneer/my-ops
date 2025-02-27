<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionTypeRequest;
use App\Models\TransactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionTypeController extends Controller
{
    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.finance-master-data.transaction-types.index');
    }


    public function data(): JsonResponse
    {
        $data = TransactionType::with('debitAccount', 'creditAccount')
            ->paginate(self::$perPage);
        return response()->json($data);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $transactionType = TransactionType::search($search)->query(function ($query) {
            $query->with('debitAccount', 'creditAccount');
        })->paginate(self::$perPage);

        return response()->json($transactionType);
    }


    public function store(TransactionTypeRequest $request): JsonResponse
    {
        TransactionType::create([
            'name' => $request->name,
            'debit_account_id' => $request->debit_account_id,
            'credit_account_id' => $request->credit_account_id
        ]);

        return response()->json(['message' => 'Tipe transaksi berhasil ditambahkan']);
    }


    public function edit(TransactionType $transactionType): JsonResponse
    {
        return response()->json($transactionType);
    }


    public function update(TransactionType $transactionType, TransactionTypeRequest $request)
    {
        $transactionType->update([
            'name' => $request->name,
            'debit_account_id' => $request->debit_account_id,
            'credit_account_id' => $request->credit_account_id
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }


    public function destroy(Request $request, TransactionType $transactionType): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $transactionType->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }


    public function getTransactionTypes(Request $request)
    {
        $search = $request->input('search');
        $data = TransactionType::search($search)->query(function ($query) {
            $query->with('debitAccount', 'creditAccount');
        })->get();

        return $data->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        });
    }


    public function selectedTransactionType(TransactionType $transactionType): array
    {
        $data = TransactionType::where('id', $transactionType->id)->first();

        return [
            'id' => $data->id,
            'name' => $data->name
        ];
    }
}
