<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Service\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class TransactionController extends Controller
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    public function index(): View
    {
        return view('pages.transactions.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->transactionService->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->transactionService->search($request));
    }

    /**
     * @throws Throwable
     */
    public function store(TransactionRequest $request): JsonResponse
    {
        $this->transactionService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function edit(Transaction $transaction): JsonResponse
    {
        return response()->json($transaction);
    }

    public function update(TransactionRequest $request, Transaction $transaction): JsonResponse
    {
        $this->transactionService->update($request, $transaction);
        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ]);
    }


    /**
     * @throws Throwable
     */
    public function confirm(Transaction $transaction): JsonResponse
    {
        $this->transactionService->confirm($transaction);
        return response()->json();
    }


    public function destroy()
    {

    }
}
