<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SKRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'sk_type' => ['required', Rule::in('Promosi', 'Demosi', 'Mutasi')],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ];
    }
}
