<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IdentityInformationRequest extends FormRequest
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
            'religion' => ['required', Rule::in('Islam', 'Kristen', 'Hindu', 'Buddha', 'Katholik', 'Konghuchu')],
            'nik' => [
                'required',
                Rule::unique('user_identity_informations', 'nik')->ignore($request->route('user')),
            ],
            'date_of_birth' => ['required', 'date'],
            'place_of_birth' => ['required'],
            'gender' => [
                'required',
                Rule::in('Laki Laki', 'Perempuan'),
            ],
            'home_address' => ['required'],
            'married_status' => [
                'required',
                Rule::in('TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'),
            ],
            'phone_number' => ['required'],
            'ktp_attachment' => [
                'mimes:jpeg,jpg,png',
                'max:2048',
                Rule::requiredIf(static function () use ($request) {
                    return $request->route('user') === null;
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK tidak boleh kosong',
            'nik.unique' => 'NIK sudah terdaftar',
            'date_of_birth.required' => 'tanggal lahir tidak boleh kosong',
            'place_of_birth.required' => 'tempat lahir tidak boleh kosong',
            'gender.required' => 'jenis kelamin tidak boleh kosong',
            'home_address.required' => 'alamat tidak boleh kosong',
            'married_status.required' => 'Status perkawinan tidak boleh kosong',
            'married_status.in' => 'Status perkawinan tidak valid',
            'ktp_attachment.required_if' => 'ktp tidak boleh kosong',
            'religion.required' => 'Agama Tidak boleh kosong',
            'religion.in' => 'Agama Tidak valid',
        ];
    }
}
