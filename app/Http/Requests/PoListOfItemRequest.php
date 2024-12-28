<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PoListOfItemRequest extends FormRequest
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
            'po_number' => ['required', Rule::unique('po_list_of_items', 'po_number')->ignore($request->route('poListOfItem')),],
            'invoice_number' => ['required', Rule::unique('po_list_of_items', 'invoice_number')->ignore($request->route('poListOfItem'))],
            'item_id' => ['required', 'string'],
            'date' => ['required', 'date'],
            'unit_price' => ['required'],
            'qty' => ['required'],
            'shipping_cost' => ['required'],
            'ppn' => ['nullable'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'travel_letter_receipt' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'po_number.required' => 'Nomor PO tidak boleh kosong',
            'po_number.unique' => 'Nomor PO sudah terdaftar',
            'invoice_number.unique' => 'Nomor Invoice sudah terdaftar',
            'invoice_number.required' => 'Nomor Invoice tidak boleh kosong',
            'item_id.required' => 'Nama barang tidak boleh kosong.',
            'date.required' => 'Tanggal Masuk tidak boleh kosong.',
            'unit_price.required' => 'Harga satuan tidak boleh kosong.',
            'qty.required' => 'Jumlah barang tidak boleh kosong.',
            'shipping_cost.required' => 'Biaya pengiriman tidak boleh kosong.',
            'supplier_id.required' => 'Supplier tidak boleh kosong.',
            'supplier_id.exists' => 'Supplier tidak ditemukan.',
            'travel_letter_receipt.required' => 'Resi surat jalan tidak boleh kosong.',
        ];
    }
}
