<?php

namespace App\Http\Requests\Master\AttendanceMachineInformation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceMachineInformationRequest extends FormRequest
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
            'branch_id' => [
                'required',
                'exists:branches,id',
            ],
            'version' => ['required'],
            'ip_address' => [
                'required',
                Rule::unique('attendance_machine_information', 'ip_address')->ignore(request()->route('attendanceMachineInformation'))],
            'port' => ['required'],
            'key' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang tidak boleh kosong',
            'branch_id.exists' => 'Cabang ini tidak terdaftar',
            'ip_address.required' => 'IP Address tidak boleh kosong',
            'ip_address.unique' => 'IP address telah terdaftar',
            'key.required' => 'Key tidak boleh kosong',
        ];
    }
}
