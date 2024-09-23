<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
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
            'branch_id' => ['nullable', 'exists:branches,id'],
            'account_id' => ['required', 'exists:accounts,id'],
            'name' => ['required'],
            'unit' => ['required', 'numeric'],
            'useful_life' => ['required'],
            'price_per_unit' => ['required', 'numeric'],
            'depreciation_rate' => ['required', 'numeric'],
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.exists' => 'Cabang ini tidak terdaftar',
            'account_id.required' => 'Akun tidak boleh kosong',
            'account_id.exists' => 'Akun ini tidak terdaftar',
            'name.required' => 'Nama tidak boleh kosong',
            'unit.required' => 'Unit tidak boleh kosong',
            'unit.numeric' => 'Unit harus berupa angka',
            'useful_life.required' => 'Masa manfaat tidak boleh kosong',
            'useful_life.numeric' => 'Masa manfaat harus berupa angka',
            'price_per_unit.required' => 'Harga per unit tidak boleh kosong',
            'price_per_unit.numeric' => 'Harga per unit harus berupa angka',
            'depreciation_rate.required' => 'Tarif penyesuaian tidak boleh kosong',
            'depreciation_rate.numeric' => 'Tarif penyesuaian tidak boleh kosong',
        ];
    }
}
