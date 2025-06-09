<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BranchRoleDefaultWorkTimeRequest extends FormRequest
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
            'work_time_id' => 'required|exists:work_time,id',
        ];
    }


    public function messages(): array
    {
        return [
            'work_time_id.required' => 'Jam kerja tidak boleh kosong',
            'work_time_id.exists' => 'Jam kerja tidak ditemukan',
        ];
    }
}
