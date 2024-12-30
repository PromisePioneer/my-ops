<?php

namespace App\Http\Requests;

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
    
    public function rules(Request $request): array
    {
        return [
            'date' => ['required', 'date'],
            'clock_in' => 'nullable',
            'clock_out' => ['nullable'],
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


