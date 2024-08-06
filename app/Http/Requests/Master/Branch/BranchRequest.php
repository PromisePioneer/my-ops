<?php

namespace App\Http\Requests\Master\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                Rule::unique('branches', 'code')
                    ->ignore(request()->route('branch')),
            ],
            'name' => [
                'required',
                Rule::unique('branches', 'name')
                    ->ignore(request()->route('branch')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode tidak boleh kosong',
            'code.unique' => 'Kode sudah terdaftar.',
            'name.required' => 'Nama tidak boleh kosong',
            'name.unique' => 'Nama sudah terdaftar.',
        ];
    }
}
