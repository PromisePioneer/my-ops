<?php

namespace App\Http\Requests\JournalAdjustment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InitialJournalRequest extends FormRequest
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
            'description' => ['required'],
            'sub_account_debit' => [
                'required',
                Rule::exists('sub_accounts', 'id'),
            ],
            'sub_account_credit' => [
                'required',
                Rule::exists('sub_accounts', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'Deskripsi tidak boleh kosong',
            'sub_account_debit.required' => 'Akun debit tidak boleh kosong',
            'sub_account_credit.required' => 'Akun kredit tidak boleh kosong',
            'sub_account_debit.exists' => 'Akun yang dipilih tidak ada',
            'sub_account_credit.exists' => 'Akun yang dipilih tidak ada',
        ];
    }
}
