<?php

namespace App\Http\Requests\ADMS;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ManageShiftRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'clock_in' => ['required', 'date_format:H:i'],
            'clock_out' => ['required', 'date_format:H:i', 'after:clock_in'],
            'time_to_checkin' => ['required', 'date_format:H:i'],
            'time_to_checkout' => ['required', 'date_format:H:i', 'after:time_to_checkin'],
            'end_time_to_checkout' => ['required', 'date_format:H:i', 'after:time_to_checkout'],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'clock_in.required' => 'Jam masuk tidak boleh kosong',
            'clock_in.date_format' => 'Jam masuk tidak valid',
            'clock_out.required' => 'Jam pulang tidak boleh kosong',
            'clock_out.date_format' => 'Jam pulang tidak valid',
            'clock_out.after' => 'Jam pulang harus sebelum jam masuk',
            'time_to_checkin.required' => 'waktu check-in tidak boleh kosong',
            'time_to_checkin.date_format' => 'waktu check-in tidak valid',
            'time_to_checkout.after' => 'waktu check-out harus sesudah wektu check-in',
            'end_time_to_checkout.required' => 'waktu check-out berakhir tidak boleh kosong',
            'end_time_to_checkout.date_format' => 'waktu check-out berakhir tidak valid',
            'end_time_to_checkout.after' => 'waktu check-out berakhir harus sesudah wektu check-out',
        ];
    }
}
