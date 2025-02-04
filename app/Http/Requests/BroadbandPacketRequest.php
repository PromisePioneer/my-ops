<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BroadbandPacketRequest extends FormRequest
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
            'branch_id' => ['required', Rule::exists('branches', 'id')],
            'name' => ['required', 'string'],
            'capacity' => ['required', 'numeric', 'min:1'],
            'price' => ['required', 'numeric', 'min:1']
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong.',
            'branch_id.exists' => 'Cabang tidak ditemukan.',
            'name.required' => 'Nama paket tidak boleh kosong.',
            'capacity.required' => 'Nama paket tidak boleh kosong.',
            'capacity.numeric' => 'Kapasitas paket harus berupa angka.',
            'capacity.min' => 'Kapasitas paket harus lebih besar dari 0.',
            'price.required' => 'Harga tidak boleh kosong',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga harus lebih besar dari 0.',
        ];
    }
}
