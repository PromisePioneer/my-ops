<?php

namespace App\Http\Requests;

use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InitialBalanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(Request $request): array
    {

        $ifHasTransaction = $this->ifHasTransaction($request);
        return [
            'branch_id' => [Rule::requiredIf(empty($request->user()->branch_id)), 'exists:branches,id', $ifHasTransaction],
            'account_id' => ['required', 'exists:accounts,id', $this->uniqueYear($request)],
            'amount' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong',
            'date.required' => 'Tanggal tidak boleh kosong.',
            'date.date' => 'Tanggal tidak valid.',
            'account_id.required' => 'Account tidak boleh kosong.',
            'account_id.exists' => 'Account tidak valid.',
            'amount.required' => 'Saldo tidak boleh kosong.',
        ];
    }


    private function uniqueYear(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $getYear = Carbon::parse($request->date)->year;
            $isAccountTransactionExists = AccountTransaction::whereYear('date', $getYear)
                ->where('branch_id', $request->branch_id)
                ->where('account_id', $request->account_id)
                ->where('transaction_type', 'SA')->exists();


            if ($request->route('accountTransaction')) {
                return null;
            }

            if ($isAccountTransactionExists) {
                return $fail('Saldo awal sudah terdaftar!');
            }

            return null;
        };
    }


    public function ifHasTransaction(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $account = Account::where('id', $request->account_id)->first();
            $transactions = $account->join(
                'account_transactions',
                'account_transactions.account_id',
                '=',
                'accounts.id'
            )->where('account_transactions.branch_id', $request->user()->branch_id ?? $request->branch_id)
                ->where('account_transactions.transaction_type', 'TR')
                ->whereYear('account_transactions.date', AccountingPeriod::first()->year)
                ->where('accounts.id', $account->id)
                ->select('account_transactions.id as account_transaction_id')
                ->first();


            if ($transactions) {
                return $fail('Akun ini sudah mempunyai transaksi. ');
            }

            return null;
        };
    }

}
