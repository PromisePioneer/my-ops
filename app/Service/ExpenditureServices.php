<?php

namespace App\Service;

use App\Http\Requests\Transaction\Expenditure\ExpenditureRequest;
use App\Models\Expenditure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenditureServices
{
    private AccountTransactionService $accountTransactionService;

    public function __construct()
    {
        $this->accountTransactionService = new AccountTransactionService;
    }

    public function store(ExpenditureRequest $request): void
    {
        $data = $request->validated();
        $data['branch_id'] = $request->user()->branch_id;
        $data['file'] = self::handleFileUpload($request);
        Expenditure::create($data);
    }

    public function update(ExpenditureRequest $request, Expenditure $expenditure): void
    {
        $data = $request->validated();
        $data['file'] = $request->file('file') ? self::handleFileUpload($request, $expenditure) : $expenditure->file;
        $expenditure->update($data);
    }

    private static function handleFileUpload(ExpenditureRequest $request, ?Expenditure $expenditure = null): string
    {
        if ($expenditure) {
            Storage::files($expenditure->file);
        }

        return $request->file('file')->store('/documents/expenditure', 'public');
    }

    public function confirm(Expenditure $expenditure): void
    {
        DB::transaction(function () use ($expenditure) {
            $this->accountTransactionService->createDebitTransaction($expenditure->description, $expenditure->amount, null, $expenditure->debit_account_id);
            $this->accountTransactionService->createCreditTransaction($expenditure->description, $expenditure->amount, null, $expenditure->credit_account_id);
            $expenditure->status_confirmation = 1;
            $expenditure->save();
        });

    }
}
