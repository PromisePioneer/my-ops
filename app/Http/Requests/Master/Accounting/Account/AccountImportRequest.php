<?php

namespace App\Http\Requests\Master\Accounting\Account;

use Illuminate\Foundation\Http\FormRequest;

class AccountImportRequest extends FormRequest
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
            'file_import' => ['required', 'file', 'mimes:xlsx,xls'],
        ];
    }

    public function messages(): array
    {
        return [
            'file_import.required' => 'file tidak boleh kosong',
            'file_import.file' => 'file tidak valid',
            'file_import.mimes' => 'file tidak valid',
        ];
    }
}
