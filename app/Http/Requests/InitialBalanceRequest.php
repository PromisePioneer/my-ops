<?php

namespace App\Http\Requests;

use App\Models\AccountTransaction;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
            'account_id' => ['required', 'exists:accounts,id', $this->uniqueYear($request)],
            'amount' => ['required', 'numeric'],
        ];
    }


    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal tidak boleh kosong.',
            'date.date' => 'Tanggal tidak valid.',
            'account_id.required' => 'Account tidak boleh kosong.',
            'account_id.exists' => 'Account tidak valid.',
            'amount.required' => 'Saldo tidak boleh kosong.',
            'amount.numeric' => 'Saldo tidak valid.',
        ];
    }


    function uniqueYear(Request $request): \Closure
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
}
