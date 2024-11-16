<?php

namespace App\Http\Requests\Transaction\Fab;

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
            'contact_id' => ['required', Rule::exists('contacts', 'id'),
                Rule::unique('fab', 'contact_id')->ignore($request->route('fab'))],
            'pic' => ['required', Rule::exists('users', 'id')],
            'fabServices.*.service_category_id' => ['required', Rule::exists('services_categories', 'id')],
            'fabServices.*.price' => ['required'],
            'fabServices.*.unit_type_id' => ['required'],
            'file_po' => ['required', 'mimes:pdf']
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Tanggal tidak valid.',
            'contact_id.required' => 'Kontak tidak boleh koosong.',
            'contact_id.exists' => 'Kontak tidak ditemukan.',
            'pic.required' => 'PIC harus diisi.',
            'pic.exists' => 'PIC tidak ditemukan.',
            'fabServices.*.service_category_id.required' => 'Kategori Layanan tidak boleh kosong.',
            'fabServices.*.service_category_id.exists' => 'Kategori Layanan tidak ditemukan.',
            'fabServices.*.capacity.required' => 'Kapasitas tidak boleh kosong.',
            'fabServices.*.unit_type_id.required' => 'Satuan tidak boleh kosong.',
            'fabServices.*.price.required' => 'Harga layanan tidak boleh kosong.',
            'file_po.required' => 'File PO tidak boleh koosng.',
            'file_po.mimes' => 'File PO harus bertipe PDF',
        ];
    }
}
