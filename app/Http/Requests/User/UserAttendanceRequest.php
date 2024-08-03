<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserAttendanceRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'user_id' => ['required', 'exists:users,id'],
            'clock_in' => ['required', 'date_format:H:i'],
            'clock_out' => ['required', 'date_format:H:i', 'after:clock_in'],
            'status_if_not_present' => [
                'nullable',
                Rule::in('Absen', 'Izin', 'Sakit', 'Cuti')
            ],
        ];
    }
}
