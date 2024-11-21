<?php

namespace App\Http\Requests;

use App\Models\EmployeeSchedule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeScheduleRequest extends FormRequest
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
            'work_time_id' => ['required'],
            // 'employee_id' => ['required', 'exists:users,absent_id'],
            'status' => ['required', Rule::in('H', 'L')],
        ];
    }


    public function messages(): array
    {
        return [
            'date.required' => ['Tanggal tidak boleh kosong'],
            'date.date' => ['Tanggal tidak valid'],
            'work_time_id.required' => ['Jam kerja tidak boleh kosong'],
            'work_time_id.work_time' => ['Jam kerja tidak valid'],
            // 'employee_id.required' => ['Karyawan tidak boleh kosong'],
            // 'employee_id.exists' => ['Karyawan tidak valid'],
            'status.required' => ['Status tidak boleh kosong'],
            'status.in' => ['Status tidak valid'],
        ];
    }
}
