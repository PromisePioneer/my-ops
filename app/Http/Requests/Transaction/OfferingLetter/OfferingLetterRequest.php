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
            'data.*.price' => ['required'],
            'serviceDescription.*.text' => ['required'],
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
            'data.*.price.required' => 'Harga layanan tidak boleh kosong.',
            'serviceDescription.*.text.required' => 'Syarat Ketentuan Layanan tidak boleh kosong.',
        ];
    }
}
