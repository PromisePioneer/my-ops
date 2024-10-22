<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AttendanceCorrectionRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        $date = Carbon::parse($request->date);
        return [
            'date' => ['required', 'date'],
            'clock_in' => 'required',
            'clock_out' => ['required', $this->validateClockOut($request)],
        ];
    }


    public function validateClockOut(Request $request): \Closure
    {
        return function ($attribute, $value, $fail) use ($request) {
            if (strtotime($value) < strtotime($request->clock_in)) {
                return $fail("Clock out harus lebih dari Clock in.");
            }

            return null;
        };
    }
}


