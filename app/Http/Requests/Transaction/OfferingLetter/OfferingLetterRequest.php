<?php

namespace App\Http\Requests\Transaction\OfferingLetter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferingLetterRequest extends FormRequest
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
            'regarding' => ['required', 'string'],
            'date' => ['required', 'date'],
            'data.*.service_category_id' => [
                'required',
                Rule::exists('services_categories', 'id'),
            ],
            'data.*.capacity' => ['required'],
            'data.*.unit_type_id' => ['required'],
            'data.*.price' => ['required'],
            'serviceDescription.*.skl_id' => ['required'],
            'pic' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'contact_id.required' => 'Kontak tidak boleh kosong.',
            'contact_id.exists' => 'Kontak tidak ditemukan.',
            'offering_number.required' => 'Nomor penawaran tidak boleh kosong.',
            'offering_number.unique' => 'Nomor penawaran sudah terdaftar.',
            'date.required' => 'Tgl. penawaran tidak boleh kosong.',
            'regarding.required' => 'Lampiran tidak boleh kosong.',
            'data.*.service_category_id.required' => 'Kategori layanan tidak boleh kosong.',
            'data.*.service_category_id.exists' => 'Kategori layanan tidak ditemukan.',
            'data.*.capacity' => 'Kapasitas tidak boleh kosong',
            'data.*.price.required' => 'Harga layanan tidak boleh kosong.',
            'serviceDescription.*.skl_id.required' => 'Syarat Ketentuan Layanan tidak boleh kosong.',
            'pic.required' => 'Penanggung jawab  tidak boleh kosong.',
            'capacity.required' => 'Kapasitas tidak boleh kosong.',
        ];
    }
}
