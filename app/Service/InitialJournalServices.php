<?php


namespace App\Service;

use App\Models\Account;
use App\Models\AccountTransaction;
use Exception;
use Illuminate\Support\Facades\DB;

class InitialJournalServices
{

    public function selectAccountDebit($request): array
    {
        if ($request->search === '') {
            $account = Account::where('branch_id', $request->user()->branch_id)
                ->join('sub_accounts', 'accounts.id', '=', 'sub_accounts.account_id')
                ->where('accounts.code', '114')
                ->orderBy('sub_accounts.name', 'asc')
                ->select('sub_accounts.id', 'sub_accounts.name')
                ->limit(10)
                ->get();
        } else {
            $account = Account::where('branch_id', $request->user()->branch_id)
                ->join('sub_accounts', 'accounts.id', '=', 'sub_accounts.account_id')
                ->where('sub_accounts.code', 'like', '%' . $request->search . '%')
                ->orWhere('sub_accounts.name', 'like', '%' . $request->search . '%')
                ->where('accounts.code', '114')
                ->orderBy('sub_accounts.name', 'asc')
                ->select('sub_accounts.id', 'sub_accounts.name')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($account as $c) {
            $response[] = array(
                "id" => $c->id,
                "text" => $c->name
            );
        }

        return $response;
    }


    public function selectAccountCredit($request): array
    {
        if ($request->search === '') {
            $account = Account::where('branch_id', $request->user()->branch_id)
                ->join('sub_accounts', 'accounts.id', '=', 'sub_accounts.account_id')
                ->where('accounts.code', '111')
                ->orderBy('sub_accounts.name', 'asc')
                ->select('sub_accounts.id', 'sub_accounts.name')
                ->limit(10)
                ->get();
        } else {
            $account = Account::where('branch_id', $request->user()->branch_id)
                ->join('sub_accounts', 'accounts.id', '=', 'sub_accounts.account_id')
                ->where('sub_accounts.code', 'like', '%' . $request->search . '%')
                ->orWhere('sub_accounts.name', 'like', '%' . $request->search . '%')
                ->where('accounts.code', '111')
                ->orderBy('sub_accounts.name', 'asc')
                ->select('sub_accounts.id', 'sub_accounts.name')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($account as $c) {
            $response[] = array(
                "id" => $c->id,
                "text" => $c->name
            );
        }

        return $response;
    }


    public function confirm($initialJournal): array
    {
        DB::beginTransaction();
        try {

            $initialJournal->status_confirmation = 1;
            $initialJournal->save();

            $this->accountTransaction($initialJournal);

            DB::commit();
            return [
                'success' => true,
                'message' => 'data berhasil dikonfirmasi'
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function accountTransaction($initialJournal): array
    {
        DB::beginTransaction();
        try {
            AccountTransaction::create([
                'date' => date('Y-m-d'),
                'sub_account_id' => $initialJournal->sub_account_debit,
                'description' => $initialJournal->description,
                'debit' => $initialJournal->initial_payment,
                'credit' => 0
            ]);

            AccountTransaction::create([
                'date' => date('Y-m-d'),
                'sub_account_id' => $initialJournal->sub_account_credit,
                'description' => $initialJournal->description,
                'debit' => 0,
                'credit' => $initialJournal->initial_payment
            ]);
            DB::commit();
            return [
                'success' => true,
                'message' => 'Data berhasil disimpan'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
