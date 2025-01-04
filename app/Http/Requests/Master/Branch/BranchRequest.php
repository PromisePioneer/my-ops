<?php

namespace App\Http\Requests\Master\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(Request $request): array
    {
        return [
            'code' => [
                Rule::requiredIf($request->has('parent_id')),
//                Rule::unique('branches', 'code')
//                    ->ignore($this->route('branch')),
            ],
            'name' => [
                'required',
                Rule::unique('branches', 'name')
                    ->ignore($this->route('branch')),
            ],
            'address' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode tidak boleh kosong',
            'code.unique' => 'Kode sudah terdaftar.',
            'name.required' => 'Nama tidak boleh kosong',
            'name.unique' => 'Nama sudah terdaftar.',
            'address.required' => 'Alamat tidak boleh kosong',
        ];
    }
}
