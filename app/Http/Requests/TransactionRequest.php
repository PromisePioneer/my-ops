<?php

namespace App\Http\Requests;

use App\Models\AccountTransaction;
use App\Models\Master\Common\Branch;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'branch_id' => [Rule::exists('branches', 'id')],
            'detail' => ['required'],
            'item_id' => [
                Rule::requiredIf($request->type === 'Barang'),
                Rule::exists('item_collections', 'id')],
            'qty' => ['required', 'numeric'],
            'unit_price' => ['required'],
            'debit_account_id' => ['required', Rule::exists('accounts', 'id')],
            'credit_account_id' => ['required', Rule::exists('accounts', 'id'),],
            'attachment' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong',
            'branch_id.exists' => 'Cabang tidak ditemukan',
            'detail.required' => 'Detail tidak boleh kosong',
            'goods_id.required' => 'Barang tidak boleh kosong',
            'goods_id.exists' => 'Barang tidak ditemukan',
            'qty.required' => 'Qty tidak boleh kosong',
            'qty.numeric' => 'Qty harus berupa angka',
            'unit_price.required' => 'Harga tidak boleh kosong',
            'unit_price.numeric' => 'Harga harus berupa angka',
            'debit_account_id.required' => 'Akun Debit tidak boleh kosong',
            'debit_account_id.exists' => 'Akun Debit tidak ditemukan',
            'credit_account_id.required' => 'Akun Kredit tidak boleh kosong',
            'credit_account_id.exists' => 'Akun Kredit tidak ditemukan',
            'attachment.required' => 'Bukti Transaksi tidak boleh kosong',
            'attachment.mimes' => 'Bukti Transaksi harus berupa jpg,jpeg,png',
            'attachment.max' => 'Ukuran Bukti Transaksi maksimal 2 Mb',
        ];
    }


    public function accountBalanceCheck(Request $request): \Closure
    {
        return static function ($value, $attribute, $fail) use ($request) {
            $date = Carbon::now();
            $branch = Branch::where('id', $request->input('branch_id'))->first();
            $accountTransactionDebit = AccountTransaction::where('account_id', $request->credit_account_id)
                ->where('branch_id', $request->input('branch_id'))
                ->where('entries_type', 'debit')
                ->whereBetween('date', [$date->copy()->subYear()->format('Y-m-d'), $date->format('Y-m-d')])
                ->sum('amount');

            $accountTransactionCredit = AccountTransaction::where('account_id', $request->credit_account_id)
                ->where('branch_id', $request->input('branch_id'))
                ->where('entries_type', 'credit')
                ->whereBetween('date', [$date->copy()->subYear()->format('Y-m-d'), $date->format('Y-m-d')])
                ->sum('amount');

            $formattedValue = str_replace('.', '', $request->input('unit_price'));
            $formattedValue = str_replace(',', '.', $formattedValue);
            $unitPrice = (float)$formattedValue;

            $subtractBetweenDebitAndCreditTransaction = (float)$accountTransactionDebit - (float)$accountTransactionCredit;
            $totalTransaction = $request->input('qty') * $unitPrice;


            if ($totalTransaction > $subtractBetweenDebitAndCreditTransaction) {
                $fail('Saldo Kurang!,' . '<br>' . 'Saldo sisa : ' . 'Rp.' . number_format($subtractBetweenDebitAndCreditTransaction, 2, '.', '.') . '<br>' . 'Total Transaksi : ' . number_format($totalTransaction, 2, '.', '.'));
            }

            return true;
        };
    }

}
