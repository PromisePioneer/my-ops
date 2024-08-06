<?php

namespace App\Http\Requests\Transaction\Bast;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BastRequest extends FormRequest
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
            'bast_number' => ['required'],
            'date' => ['required', 'date'],
            'first_party_identity_name' => ['required'],
            'first_party_position' => ['required'],
            'objective' => ['required', 'string'],
            'file' => [
                Rule::requiredIf(function () {
                    return request()->route('bast') === null;
                }),
                'mimes:pdf',
                'max:2048',
            ],
            'data.*.product_name' => ['required'],
            'data.*.qty' => ['required', 'string'],
            'data.*.serial_number' => ['required'],
            'data.*.description' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'bast_number.required' => 'Nomor bast tidak boleh kosong.',
            'contact_id.required' => 'Contact id tidak boleh kosong.',
            'contact_id.exists' => 'Contact id tidak ditemukan.',
            'date.required' => 'Date tidak boleh kosong.',
            'date.date' => 'Date harus berupa tanggal.',
            'first_party_identity_name.required' => 'First party identity name tidak boleh kosong.',
            'first_party_position.required' => 'First party position tidak boleh kosong.',
            'objective.required' => 'Objective tidak boleh kosong.',
            'file.required_if' => 'File tidak boleh kosong.',
            'file.mimes' => 'File harus berupa pdf.',
            'file.max' => 'File tidak boleh lebih dari 2MB.',
            'data.*.product_name.required' => 'Produk tidak boleh kosong.',
            'data.*.qty.required' => 'Jumlah tidak boleh kosong.',
            'data.*.serial_number.required' => 'Serial number tidak boleh kosong.',

        ];
    }
}
