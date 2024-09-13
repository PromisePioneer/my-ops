<?php

namespace App\Http\Requests\Allowances;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MealAllowanceRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'type' => [
                'required',
                Rule::in('Manual Input', 'Sesuai Kehadiran'),
            ],
            'amount' => [
                Rule::requiredIf(function () use ($request) {
                    return $request->type === 'Manual Input';
                }),
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'user_id.required' => 'Karyawan tidak boleh kosong',
            'user_id.exists' => 'Karyawan tidak ditemukan',
            'type.required' => 'Tipe tidak boleh kosong',
            'type.in' => 'Tipe tidak valid',
            'amount.required' => 'Nominal tidak boleh kosong',
        ];
    }
}
