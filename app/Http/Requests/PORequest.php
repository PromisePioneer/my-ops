<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PORequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string'],
            'contact_id' => ['required', 'integer', 'exists:contacts,id'],
            'date' => ['required', 'date'],
            'pic' => ['required', 'exists:users,id'],
            'data.*.item' => ['required', 'string'],
            'data.*.qty' => ['required'],
            'data.*.unit_type_id' => ['required', 'integer', 'exists:unit_types,id'],
            'data.*.price' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'subject.required' => 'Subjek tidak boleh kosong',
            'contact_id.required' => 'Kontak tidak boleh kosong',
            'date.required' => 'Tanggal tidak boleh kosong ',
            'date.date' => 'Tanggal tidak valid',
            'pic.required' => 'PIC tidak boleh kosong',
            'pic.exists' => 'PIC tidak valid',
            'data.*.item.required' => 'Item tidak boleh kosong',
            'data.*.qty.required' => 'Qty tidak boleh kosong',
            'data.*.unit_type_id.required' => 'Satuan tidak boleh kosong',
            'data.*.price.required' => 'Harga tidak boleh kosong',
        ];
    }
}
