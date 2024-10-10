<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class InitialBalanceRequest extends FormRequest
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
            'account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric'],
        ];
    }


    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal tidak boleh kosong.',
            'date.date' => 'Tanggal tidak valid.',
            'account_id.required' => 'Account tidak boleh kosong.',
            'account_id.exists' => 'Account tidak valid.',
            'amount.required' => 'Saldo tidak boleh kosong.',
            'amount.numeric' => 'Saldo tidak valid.',
        ];
    }
}
