<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ODPAreaRequest extends FormRequest
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
            'code' => [
                'required',
                Rule::unique('odp_areas', 'code')->ignore($request->route('odpArea')),
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'code.required' => ['Kode area tidak boleh kosong.'],
            'code.unique' => ['Kode area sudah terdaftar.'],
        ];
    }
}
