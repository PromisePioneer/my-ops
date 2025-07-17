<?php

namespace App\Http\Requests;

use AllowDynamicProperties;
use App\Enum\Contact\TaxType;
use App\Models\AccountingPeriod;
use App\Models\AccountTransaction;
use App\Models\Master\Common\Branch;
use App\Models\Master\Common\Contact;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

#[AllowDynamicProperties] class TransactionRequest extends FormRequest
{
    public function __construct()
    {
        parent::__construct();
        $this->contact = new Contact();
    }

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
        $supplier = $this->contact->query()->find($request->supplier_id);


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
                Rule::requiredIf($supplier?->tax_type === TaxType::PKP->value && $this->route('transaction') === null),
                'mimes:jpg,jpeg,png', 'max:2048'],
            'qty_in_meter' => ['numeric'],
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
            $mainBranch = Branch::with('parent')->find($request->input('branch_id'));

            $creditTransaction = AccountTransaction::with('account')
                ->where('account_id', $request->input('credit_account_id'))
                ->where('branch_id', $mainBranch->parent_id)
                ->where('entries_type', 'debit')
                ->whereYear('date', AccountingPeriod::first()->year)
                ->sum('amount');


            $formattedValue = str_replace('.', '', $request->input('unit_price'));
            $formattedValue = str_replace(',', '.', $formattedValue);
            $unitPrice = (float)$formattedValue;


            $totalTransaction = $request->input('qty') * $unitPrice;

            if ($totalTransaction > $creditTransaction) {
                $fail('Saldo Kurang!,' . '<br>' . 'Saldo sisa : ' . 'Rp.' . number_format($creditTransaction, 2, '.', '.') . '<br>' . 'Total Transaksi : ' . number_format($totalTransaction, 2, '.', '.'));
            }

            return true;
        };
    }

}
