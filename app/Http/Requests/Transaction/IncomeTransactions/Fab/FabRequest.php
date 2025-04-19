<?php

namespace App\Http\Requests\Transaction\IncomeTransactions\Fab;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FabRequest extends FormRequest
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
     */
    public function rules(Request $request): array
    {
        return [
            'date' => ['required', 'date'],
            'pic' => ['required', Rule::exists('users', 'id')],
            'po_id' => ['required', Rule::exists('purchase_orders', 'id'), Rule::unique('fab', 'po_id')->ignore($request->route('fab'))],
            'fabServices.*.service_category_id' => ['required', Rule::exists('service_categories', 'id')],
            'fabServices.*.price' => ['required'],
            'fabServices.*.unit_type_id' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Tanggal tidak valid.',
            'pic.required' => 'PIC harus diisi.',
            'pic.exists' => 'PIC tidak ditemukan.',
            'fabServices.*.service_category_id.required' => 'Kategori Layanan tidak boleh kosong.',
            'fabServices.*.service_category_id.exists' => 'Kategori Layanan tidak ditemukan.',
            'fabServices.*.capacity.required' => 'Kapasitas tidak boleh kosong.',
            'fabServices.*.unit_type_id.required' => 'Satuan tidak boleh kosong.',
            'fabServices.*.price.required' => 'Harga layanan tidak boleh kosong.',
        ];
    }
}
