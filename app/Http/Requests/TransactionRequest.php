<?php

namespace App\Http\Requests;

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
            'goods_id' => [
                Rule::requiredIf($request->type === 'Barang'),
                Rule::exists('goods', 'id')],
            'qty' => ['required', 'numeric'],
            'unit_price' => ['required', 'numeric'],
            'debit_account_id' => ['required', Rule::exists('accounts', 'id')],
            'credit_account_id' => ['required', Rule::exists('accounts', 'id')],
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
        ];
    }

}
