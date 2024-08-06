<?php

namespace App\Http\Requests\Master\Branch;

use Illuminate\Foundation\Http\FormRequest;

class BranchImportRequest extends FormRequest
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
            'file_import.required' => 'File Import tidak boleh kosong',
            'file_import.file' => 'File Import tidak valid',
            'file_import.mimes' => 'File Import tidak valid',
        ];
    }
}
