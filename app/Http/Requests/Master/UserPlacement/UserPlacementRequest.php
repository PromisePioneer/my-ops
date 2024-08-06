<?php

namespace App\Http\Requests\Master\UserPlacement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserPlacementRequest extends FormRequest
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
            'code' => [
                'required',
                Rule::unique('user_placements', 'code')->ignore(request()->route('userPlacement')),
            ],
            'name' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'kode tidak boleh kosong',
            'code.unique' => 'kode sudah terdaftar',
            'name.required' => 'nama tidak boleh kosong',
        ];
    }
}
