<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JointClosureAreaRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'code' => [
                'required',
                Rule::unique('joint_closures_area', 'code')
                    ->ignore($request->route('jointClosureArea')),
            ],
            'branch_id' => [
                'required',
                Rule::exists('branches', 'id'),
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'code.required' => ['Kode tidak boleh kosong'],
            'code.unique' => ['Kode sudah ada'],
            'branch_id.required' => ['Kode tidak boleh kosong'],
            'branch_id.exists' => ['Kode tidak ditemukan'],
        ];
    }
}
