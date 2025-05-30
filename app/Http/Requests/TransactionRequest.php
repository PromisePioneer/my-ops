<?php

namespace App\Http\Requests;

use App\Models\AccountTransaction;
use App\Models\Master\Common\Branch;
use App\Models\Supplier;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(Request $request): array
    {
        $supplier = Supplier::where('id', $request->supplier_id)->first();


        return [
            'branch_id' => [Rule::exists('branches', 'id')],
            'detail' => ['required'],
            'item_id' => [
                Rule::requiredIf($request->type === 'Barang'),
                Rule::exists('item_collections', 'id')],
            'qty' => ['required', 'numeric'],
            'unit_price' => ['required', $this->ifLessThanZero($request)],
            'debit_account_id' => ['required', Rule::exists('accounts', 'id')],
            'credit_account_id' => ['required', Rule::exists('accounts', 'id'), $this->accountBalanceCheck($request)],
            'attachment' => [
                Rule::requiredIf($this->route('transaction') === null),
                'mimes:jpg,jpeg,png', 'max:2048'],
            'tax_invoice' => [
                Rule::requiredIf($supplier?->tax_type === 'PKP' && $this->route('transaction') === null),
                'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong',
            'branch_id.exists' => 'Cabang tidak ditemukan',
            'detail.required' => 'Detail tidak boleh kosong',
            'item_id.required' => 'Barang tidak boleh kosong',
            'item_id.exists' => 'Barang tidak ditemukan',
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
            'tax_invoice.required' => 'Faktur Pajak tidak boleh kosong',
            'tax_invoice.mimes' => 'Faktur Pajak harus berupa jpg,jpeg,png',
        ];
    }


    public function ifLessThanZero(Request $request): Closure
    {
        return static function ($value, $attribute, $fail) use ($request) {
            if ($request->input('unit_price') <= 0) {
                $fail('Harga harus lebih dari 0');
            }
        };
    }


    public function accountBalanceCheck(Request $request): Closure
    {
        return static function ($value, $attribute, $fail) use ($request) {
            $date = Carbon::now();
            $mainBranch = Branch::with('parent')->find($request->input('branch_id'));
            $accountTransactionDebit = AccountTransaction::where('account_id', $request->credit_account_id)
                ->where('branch_id', $mainBranch->parent_id)
                ->where('entries_type', 'debit')
                ->whereBetween('date', [$date->copy()->subYear()->format('Y-m-d'), $date->format('Y-m-d')])
                ->sum('amount');

            $accountTransactionCredit = AccountTransaction::where('account_id', $request->credit_account_id)
                ->where('branch_id', $mainBranch->parent_id)
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
