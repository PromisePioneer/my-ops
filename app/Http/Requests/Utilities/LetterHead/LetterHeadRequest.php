<?php

namespace App\Http\Requests\Utilities\LetterHead;

use Illuminate\Foundation\Http\FormRequest;

class LetterHeadRequest extends FormRequest
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
            'header' => ['mimes:jpg,jpeg,png', 'max:2048'],
            'footer' => ['mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }


    public function messages(): array
    {
        return [
            'header.mimes' => 'KOP Header harus bertipe: jpg, jpeg, png.',
            'footer.mimes' => 'KOP Footer harus bertipe: jpg, jpeg, png.',
        ];
    }
}
