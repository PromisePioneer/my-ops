<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AttendancesSummaryFilterByDateRequest extends FormRequest
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
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }


    public function messages(): array
    {
        return [
            'start_date.required' => 'Tanggal awal tidak boleh kosong',
            'start_date.date' => 'Tanggal awal harus berupa tanggal',
            'end_date.required' => 'Tanggal akhir tidak boleh kosong',
            'end_date.date' => 'Tanggal akhir harus berupa tanggal',
            'end_date.after_or_equal' => 'Tanggal akhir harus setelah tanggal awal',
        ];
    }
}
