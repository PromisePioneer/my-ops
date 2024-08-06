<?php

namespace App\Http\Requests\Transaction\Fab;

use Illuminate\Foundation\Http\FormRequest;
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
    public function rules(): array
    {

        return [
            'contact_id' => [
                'required',
                Rule::exists('contacts', 'id'),
            ],
            'fab_number' => [
                'required',
                Rule::unique('fab', 'fab_number')->ignore(request()->route('fab')),
            ],
            'subscription_status' => [
                'required',
                Rule::in('baru', 'perubahan jenis layanan', 'daftar ulang'),
            ],
            'date' => ['required', 'date'],
            'billing_address' => ['required', 'string'],
            'installation_address' => ['required', 'string'],
            'zip_code' => ['required', 'max:4'],
            'file' => [
                'mimes:pdf',
                'max:2048',
                Rule::requiredIf(function () {
                    return request()->route('fab') === null;
                }),
            ],
            'data.*.service_category_id' => [
                'required',
                Rule::exists('services_categories', 'id'),
            ],
            'data.*.qty' => ['required', 'numeric'],
            'data.*.unit_price' => ['required'],

        ];
    }

    public function messages(): array
    {
        return [
            'contact_id.required' => 'Pelanggan tidak boleh kosong',
            'contact_id.exists' => 'Pelanggan tidak ditemukan',
            'fab_number.required' => 'Nomor tidak boleh kosong',
            'fab_number.unique' => 'Nomor sudah terdaftar',
            'subscription_status.required' => 'Status berlangganan tidak boleh kosong',
            'subscription_status.in' => 'Status berlangganan tidak ditemukan',
            'date.required' => 'Tanggal tidak boleh kosong',
            'billing_address.required' => 'Alamat penagihan tidak boleh kosong',
            'installation_address.required' => 'Alamat instalasi tidak boleh kosong',
            'zip_code.required' => 'Kode pos tidak boleh kosong',
            'zip_code.numeric' => 'Kode pos harus berupa angka',
            'zip_code.max' => 'Kode pos tidak boleh lebih dari 4 digit',
            'file.required' => 'File tidak boleh kosong',
            'file.mimes' => 'File harus berupa pdf',
            'data.*.service_category_id.required' => 'Jenis layanan tidak boleh kosong',
            'data.*.service_category_id.exists' => 'Jenis layanan tidak ditemukan',
            'data.*.qty.required' => 'Qty tidak boleh kosong',
            'data.*.unit_price.required' => 'Unit harga tidak boleh kosong',
        ];
    }
}
