<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransactionConfirmationRequest;
use App\Models\Transaction;
use App\Support\Transactions\TransactionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class TransactionController extends Controller
{


    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Transaction::class);
        return view('pages.transactions.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Transaction::class);
        return response()->json($this->transactionService->data());
    }

    /**
     * @throws AuthorizationException
     */
    public function filter(Request $request): JsonResponse
    {
        $this->authorize('view', Transaction::class);
        return response()->json($this->transactionService->filter($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Transaction::class);
        return response()->json($this->transactionService->search($request));
    }

    /**
     * @throws Throwable
     */
    public function store(TransactionRequest $request): JsonResponse
    {
        $this->authorize('create', Transaction::class);
        $this->transactionService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(Transaction $transaction): JsonResponse
    {
        $this->authorize('edit', Transaction::class);
        return response()->json($transaction);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(TransactionRequest $request, Transaction $transaction): JsonResponse
    {
        $this->authorize('update', Transaction::class);
        $this->transactionService->update($request, $transaction);
        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    /**
     * @throws Throwable
     */
    public function lockTransaction(Transaction $transaction): JsonResponse
    {
        $this->transactionService->lockTransaction($transaction);
        return response()->json([
            'message' => 'data berhasil dikunci'
        ]);
    }


    /**
     * @throws Throwable
     */
    public function confirm(Transaction $transaction, TransactionConfirmationRequest $request): JsonResponse
    {
        $this->transactionService->confirm($transaction, $request);
        return response()->json([
            'message' => 'data berhasil dibuka'
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Transaction $transaction): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $transaction->whereIn('id', $explodeID)->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }


    /**
     * @throws Throwable
     */
    public function confirmFromPIC(Transaction $transaction): JsonResponse
    {
        $this->transactionService->confirmFromPIC($transaction);
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

}
