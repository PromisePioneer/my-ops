<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NinePastFiveTeenLateDeductionRequest extends FormRequest
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
            'technician_id' => ['required', 'exists:users,id'],
            'total_amount_of_late' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'date.required' => 'Tanggak tidak boleh kosong',
            'date.date' => 'Tanggal tidak valid',
            'technician_id.required' => 'Teknisi belum dipilih',
            'technician_id.exists' => 'Teknisi tidak valid',
            'total_amount_of_late.required' => 'Total deduction amount tidak boleh kosong',
        ];
    }
}
