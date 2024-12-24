<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
            'branch_id' => ['required', 'exists:branches,id'],
            'po_number' => ['required'],
            'invoice_number' => ['required'],
            'name' => ['required', 'string'],
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
            'invoice_number.required' => 'Nomor Invoice tidak boleh kosong',
            'name.required' => 'Nama barang tidak boleh kosong.',
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
