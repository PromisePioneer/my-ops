<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FamilyInformationRequest extends FormRequest
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
            'partner_name' => ['required', 'string'],
            'family_dependents' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'partner_name.required' => 'nama pasangan tidak boleh kosong',
            'family_dependents.required' => 'jumlah anggota keluarga bergantung tidak boleh kosong',
        ];
    }
}
