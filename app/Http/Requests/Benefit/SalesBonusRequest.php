<?php

namespace App\Http\Requests\Benefit;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SalesBonusRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'date_active' => ['required', 'date'],
            'customer_name' => ['required'],
            'user_id' => ['required', 'exists:users,id'],
            'packet_id' => ['required', 'exists:broadband_packets,id'],
            'discount' => ['nullable', 'numeric'],
        ];
    }


    public function messages(): array
    {
        return [
            'date_active.required' => 'Tanggal aktif tidak boleh kosong',
            'date_active.date' => 'Tanggal aktif harus berupa tanggal',
            'customer_name.required' => 'Nama pelanggan tidak boleh kosong',
            'packet_id.required' => 'Paket tidak boleh kosong',
            'packet_id.exists' => 'Paket tidak ditemukan',
            'discount.numeric' => 'Diskon harus berupa angka',
        ];
    }
}
