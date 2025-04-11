<?php

namespace App\Http\Requests;

use App\Models\AccountTransaction;
use App\Models\Master\Common\Branch;
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
        return [
            'branch_id' => [Rule::requiredIf(empty($request->user()->branch_id)), 'exists:branches,id'],
            'account_id' => ['required', 'exists:accounts,id', $this->uniqueYear($request)],
            'amount' => ['required', $this->isTransactionBalance($request)],
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


    public function isTransactionBalance(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $rawAmount = $request->input('amount');
            $formattedValue = str_replace(',', '.', str_replace('.', '', $rawAmount));
            $amount = number_format((float)$formattedValue, 4, '.', '');

            $totalInitialBalance = 0;
            $date = Carbon::now()->subYear()->endOfYear();
            $creditInitialBalance = AccountTransaction::where('transaction_type', 'SA')->whereYear('date', $date)->whereMonth('date', $date)->where('branch_id', $request->input('branch_id'))->where('entries_type', 'credit')->where('account_id', $request->input('account_id'))->first();


            $debitInitialBalance = AccountTransaction::where('transaction_type', 'SA')->whereYear('date', $date)->whereMonth('date', $date)->where('branch_id', $request->input('branch_id'))->where('entries_type', 'debit')->where('account_id', $request->input('account_id'))->first();


            if ($request->input('entries_type') === 'debit') {
                $totalInitialBalance = bcsub($amount, $creditInitialBalance->amount, 2);
            }


            if ($request->input('entries_type') === 'credit') {
                $totalInitialBalance = bcsub($debitInitialBalance->amount, $amount, 2);
            }

//            if ($totalInitialBalance < 0) {
//                $fail('Transaksi tidak balance');
//            }
        };
    }


}
