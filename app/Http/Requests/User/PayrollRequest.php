<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PayrollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date'],
            'salary_date' => ['required', 'date'],
            'positional_allowance' => ['nullable'],
            'meal_allowance' => ['nullable'],
            'transportation_allowance' => ['nullable'],
            'overtime_allowance' => ['nullable'],
            'sales_bonus' => ['nullable'],
            'project_bonus' => ['nullable'],
            'other_bonus' => ['nullable'],
            'bpjs_tek_dues' => ['nullable'],
            'bpjs_kes_dues' => ['nullable']
        ];
    }
}
