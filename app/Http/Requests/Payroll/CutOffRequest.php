<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CutOffRequest extends FormRequest
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
            'attendance_period_start' => ['required', 'integer', 'between:1,31'],
            'attendance_period_end' => ['required', 'integer', 'between:1,31'],
            'payroll_period_start' => ['required', 'integer', 'between:1,31'],
            'payroll_period_end' => ['required', 'integer', 'between:1,31'],
        ];
    }

    public function messages(): array
    {
        return [
            'attendance_period_start.required' => 'Periode awal absensi tidak boleh kosong',
            'attendance_period_start.integer' => 'Periode awal absensi harus berupa angka',
            'attendance_period_start.between' => 'Periode awal absensi harus diantara angka 1 - 31',
            'attendance_period_end.required' => 'Periode akhir absensi tidak boleh kosong',
            'attendance_period_end.integer' => 'Periode akhir absensi harus berupa angka',
            'attendance_period_end.between' => 'Periode akhir absensi harus diantara angka 1 - 31',
            'payroll_period_start.required' => 'Periode awal payroll tidak boleh kosong',
            'payroll_period_start.integer' => 'Periode awal payroll harus berupa angka',
            'payroll_period_start.between' => 'Periode awal payroll harus diantara angka 1 - 31',
            'payroll_period_end.required' => 'Periode akhir payroll tidak boleh kosong',
            'payroll_period_end.integer' => 'Periode akhir payroll harus berupa angka',
            'payroll_period_end.between' => 'Periode akhir payroll harus diantara angka 1 - 31',
        ];
    }
}
