<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TransactionTypeRequest extends FormRequest
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
            'name' => ['required'],
            'debit_account_id' => ['required', 'exists:accounts,id'],
            'credit_account_id' => ['required', 'exists:accounts,id'],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'debit_account_id.required' => 'Akun Debit tidak boleh kosong',
            'debit_account_id.exists' => 'Akun Debit ini tidak terdaftar',
            'credit_account_id.required' => 'Akun Kredit tidak boleh kosong',
            'credit_account_id.exists' => 'Akun Kredit ini tidak terdaftar',
        ];
    }
}
