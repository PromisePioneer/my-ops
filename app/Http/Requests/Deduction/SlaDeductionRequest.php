<?php

namespace App\Http\Requests\Deduction;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SlaDeductionRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'technician_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal tidak boleh kosong',
            'date.date' => 'Tanggal tidak valid',
            'technician_id.required' => 'Teknisi tidak boleh kosong',
            'technician_id.exists' => 'Teknisi tidak valid',
            'amount.required' => 'Total denda tidak boleh kosong',
        ];
    }
}
