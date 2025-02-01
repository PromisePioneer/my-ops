<?php

namespace App\Http\Requests\User;

use App\Rules\UniqueLeaders;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(Request $request): array
    {
        return [
            'placement' => [
                Rule::in('Pusat', 'Cabang'),
                new UniqueLeaders($request),
                Rule::requiredIf($request->user()->placement !== 'Pusat'),
            ],
            'branch_id' => [
                Rule::requiredIf($request->user()->placement === 'Cabang'),
            ],
            'absent_id' => [
                'required',
                'max:3',
                Rule::unique('users', 'absent_id')->ignore($request->route('user')),
            ],
            'join_date' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($request->route('user')),
            ],
            'company_id' => [
                'required',
                Rule::exists('companies', 'id'),
            ],
            'roles.*' => [
                'required',
                'integer',
                Rule::exists('roles', 'id'),
            ],
            'password' => [
            ]

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
            'branch_id.required_if' => 'Branch tidak boleh kosong',
            'branch_id.integer' => 'Branch tidak valid',
            'branch_id.exists' => 'Branch tidak valid',
            'absent_id.max' => 'Absent tidak boleh lebih dari 3 karakter',
            'placement.required_if' => 'Penempatan tidak boleh kosong',
            'company_id.required' => 'Perusahaan tidak boleh kosong',
        ];
    }
}
