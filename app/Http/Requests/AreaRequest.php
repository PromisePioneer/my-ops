<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AreaRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'branch_id' => [Rule::requiredIf($request->user()->branch_id === null), Rule::exists('branches', 'id')],
            'name' => ['required', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong',
            'branch_id.exists' => 'Cabang yang dipilih tidak ada',
            'branch_id.unique' => 'Cabang yang dipilih sudah ada',
            'name.required' => 'Nama tidak boleh kosong',
        ];
    }
}
