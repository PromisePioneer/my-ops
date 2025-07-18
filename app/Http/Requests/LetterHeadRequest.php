<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LetterHeadRequest extends FormRequest
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
            'header' => [
                Rule::requiredIf($this->route('letterHead') === null),
                'mimes:jpg,png,jpeg',
                'max:2048'],
            'footer' => [
                Rule::requiredIf($this->route('letterHead') === null),
                'mimes:jpg,png,jpeg',
                'max:2048'
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'header.required' => 'Header tidak boleh kosong',
            'header.mimes' => 'Header harus berupa jpg, png, jpeg',
            'header.max' => 'Header tidak boleh lebih dari 2 Mb',
            'footer.required' => 'Footer tidak boleh kosong',
            'footer.mimes' => 'Footer harus berupa jpg, png, jpeg',
            'footer.max' => 'Footer tidak boleh lebih dari 2 MB.',
        ];
    }
}
