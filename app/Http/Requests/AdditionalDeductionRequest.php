<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdditionalDeductionRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', Rule::in('Tangga', 'Piket', 'Mobil')],
            'amount' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal tidak boleh kosong',
            'user_id.required' => 'Teknisi tidak boleh kosong.',
            'type.required' => 'Tipe Denda tidak boleh kosong.',
            'amount.required' => 'Total Denda tidak boleh kosong',
        ];
    }
}
