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
            'offering_number' => [
                'required',
                Rule::unique('offering_letters', 'offering_number')->ignore(request()->route('offeringLetter')),
            ],
            'date' => ['required', 'date'],
            'attachment' => ['required'],
            'foreword' => ['required'],
            'notes' => ['required'],
            'marketing_agent_name' => ['required'],
            'marketing_agent_contact' => ['required'],
            'file' => [
                'max:2048',
                'mimes:pdf',
                Rule::requiredIf(static function () {
                    return request()->route('offeringLetter') === null;
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
            'contact_id.required' => 'Kontak tidak boleh kosong.',
            'contact_id.exists' => 'Kontak tidak ditemukan.',
            'offering_number.required' => 'Nomor penawaran tidak boleh kosong.',
            'offering_number.unique' => 'Nomor penawaran sudah terdaftar.',
            'date.required' => 'Tgl. penawaran tidak boleh kosong.',
            'attachment.required' => 'Lampiran tidak boleh kosong.',
            'foreword.required' => 'Kata pengantar tidak boleh kosong.',
            'notes.required' => 'Catatan penawaran tidak boleh kosong.',
            'marketing_agent_name.required' => 'Agen Marketing tidak boleh kosong.',
            'marketing_agent_contact.required' => 'No. Telepon Marketing agent tidak boleh kosong.',
            'file.required' => 'File tidak boleh kosong.',
            'file.mimes' => 'File harus berformat PDF.',
            'file.max' => 'File maksimal 2 MB.',
            'data.*.service_category_id.required' => 'Kategori layanan tidak boleh kosong.',
            'data.*.service_category_id.exists' => 'Kategori layanan tidak ditemukan.',
            'data.*.qty.required' => 'Jumlah layanan tidak boleh kosong.',
            'data.*.qty.numeric' => 'Jumlah layanan harus berupa angka.',
            'data.*.unit_price.required' => 'Harga layanan tidak boleh kosong.',
        ];
    }
}
