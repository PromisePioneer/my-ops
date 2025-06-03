<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BranchHasDefaultWorkTimeRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:branches,id'],
            'work_time_id' => ['required', 'exists:work_time,id'],
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong.',
            'branch_id.exists' => 'Cabang tidak ditemukan.',
            'work_time_id.required' => 'Jam kerja tidak boleh kosong.',
            'work_time_id.exists' => 'Jam kerja tidak ditemukan.',
        ];
    }
}
