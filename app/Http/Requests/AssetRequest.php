<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
    public function rules(Request $request): array
    {
        return [
            'code' => ['required', Rule::unique('assets', 'code')->ignore($request->route('asset'))],
            'item_id' => ['required', 'exists:item_collections,id'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'date_received' => ['required', 'date'],
            'unit' => ['required', 'numeric'],
            'price_per_unit' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.exists' => 'Cabang ini tidak terdaftar',
            'code.required' => 'Kode tidak boleh kosong',
            'code.unique' => 'Kode sudah terdaftar',
            'item_id.required' => 'Item tidak boleh kosong',
            'item_id.exists' => 'Item ini tidak terdaftar',
            'credit_account_id.required' => 'Akun Kredit tidak boleh kosong',
            'credit_account_id.exists' => 'Akun Kredit ini tidak terdaftar',
            'unit.required' => 'Unit tidak boleh kosong',
            'unit.numeric' => 'Unit harus berupa angka',
            'price_per_unit.required' => 'Harga per unit tidak boleh kosong',
            'date_recieved.required' => 'Tanggal Perolehan tidak boleh kosong',
            'date_recieved.date' => 'Tanggal Perolehan harus berupa tanggal',
        ];
    }
}
