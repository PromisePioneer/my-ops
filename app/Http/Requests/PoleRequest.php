<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PoleRequest extends FormRequest
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
            'diameter' => ['required', 'integer'],
            'length' => ['required', 'integer'],
            'region' => ['required'],
            'code' => ['required'],
            'lat' => [
                'required',
                'between:-90,90',
            ],
            'long' => [
                'required',
                'between:-180,180',
            ],
            'cut_off_date' => ['required'],
        ];
    }
}
