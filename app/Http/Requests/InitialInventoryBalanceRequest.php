<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InitialInventoryBalanceRequest extends FormRequest
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
            'branch_id' => [
                Rule::requiredIf(empty($request->user()->branch_id)),
                'exists:branches,id'
            ],
            'date' => ['required', 'date_format:Y-m-d'],
            'supplier_id' => ['required', 'exists:contacts,id'],
            'detail' => ['required'],
            'item_id' => ['required', 'exists:item_collections,id'],
            'unit_price' => ['required'],
            'qty' => ['required', 'numeric', 'min:0'],
            'attachment' => [Rule::requiredIf($request->route('initialInventoryBalance') === null), 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'stock_account_id' => ['required', 'exists:accounts,id'],
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong',
            'branch_id.exists' => 'Cabang tidak ditemukan',
            'date.required' => 'Tanggal tidak boleh kosong',
            'date.date_format' => 'Tanggal tidak valid',
            'supplier_id.required' => 'Supplier tidak boleh kosong',
            'supplier_id.exists' => 'Supplier tidak ditemukan',
            'detail.required' => 'Detail tidak boleh kosong',
            'item_id.required' => 'Item tidak boleh kosong',
            'item_id.exists' => 'Item tidak ditemukan',
            'unit_price.required' => 'Harga satuan tidak boleh kosong',
            'qty.required' => 'Kuantitas tidak boleh kosong',
            'qty.numeric' => 'Kuantitas tidak valid',
            'qty.min' => 'Kuantitas tidak boleh kurang dari 0',
            'attachment.required' => 'Dokumentasi tidak boleh kosong',
            'attachment.file' => 'Dokumentasi tidak valid',
            'attachment.mimes' => 'Dokumentasi hanya berupa jpg, jpeg, png',
            'attachment.max' => 'Dokumentasi tidak boleh lebih dari 2MB',
            'stock_account_id.required' => 'Akun stok tidak boleh kosong',
            'stock_account_id.exists' => 'Akun stok tidak ditemukan',

        ];
    }
}
