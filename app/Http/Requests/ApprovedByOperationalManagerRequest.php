<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApprovedByOperationalManagerRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'operational_manager_approval' => ['required', Rule::in('Diterima', 'Revisi', 'Ditolak')],
            'reason' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'operational_manager_approval.required' => 'Status Diterima tidak boleh kosong',
        ];
    }
}
