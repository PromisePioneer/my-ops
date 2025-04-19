<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PSBRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'registration_date' => ['required', 'date'],
            'active_date' => ['required', 'date'],
            'customer_name' => ['required', 'string'],
            'phone_number' => ['required'],
            'address' => ['required'],
            'area_id' => ['required', Rule::exists('areas', 'id')],
        ];
    }
}
