<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SPRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'sp_date' => ['required', 'date'],
            'sp_type' => [
                'required',
                Rule::in('SP-1', 'SP-2', 'SP-3'),
            ],
            'reason' => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
    }
}
