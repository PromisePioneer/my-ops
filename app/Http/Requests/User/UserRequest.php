<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     */
    public function rules(Request $request): array
    {
        return [
            'placement' => [
                'required',
                Rule::in('Pusat', 'Cabang'),
            ],
            'branch_id' => [
                'integer',
                Rule::exists('branches', 'id'),
                Rule::requiredIf(static function () use ($request) {
                    return $request->branch_id;
                }),
            ],
            'absent_id' => [
                'required',
                Rule::unique('users', 'absent_id')->ignore($request->route('user')),
            ],
            'join_date' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($request->route('user')),
            ],
            'roles.*' => ['required',
                'integer',
                Rule::exists('roles', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'name.string' => 'Nama tidak valid',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nip.required' => 'nip tidak boleh kosong',
            'nip.unique' => 'nip sudah terdaftar',
            'roles.required' => 'Role tidak boleh kosong',
            'roles.integer' => 'Role tidak valid',
            'roles.exists' => 'Role tidak valid',
            'branch_id.required' => 'Branch tidak boleh kosong',
            'branch_id.integer' => 'Branch tidak valid',
            'branch_id.exists' => 'Branch tidak valid',
        ];
    }
}
