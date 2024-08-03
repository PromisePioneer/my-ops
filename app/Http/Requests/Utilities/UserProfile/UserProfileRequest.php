<?php

namespace App\Http\Requests\Utilities\UserProfile;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
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
            'profile_pic' => [
                'mimes:jpeg,jpg,png',
                'max:4000'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'profile_pic.mimes' => 'File harus berekstensi jpeg, jpg, png',
            'profile_pic.max' => 'Ukuran file terlalu besar, Maks: 4MB'
        ];
    }
}
