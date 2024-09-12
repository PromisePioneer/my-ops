<?php

namespace App\Http\Requests\Allowances;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransportationAllowanceRequest extends FormRequest
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
    public function rules(Request $request): array
    {
        return [
            'user_id.*' => ['required', 'integer', ' exists:users,id'],
            'date' => ['required', 'date'],
            'transportation_type' => ['required', Rule::in('Dibawah 15 Km', 'Diatas 15 Km')],
            'spk_image' => [
                'image', 'mimes:jpeg,jpg,png', 'max:2048', Rule::requiredIf(function () use ($request) {
                    return $request->transportation_type === 'Diatas 15 Km';
                }),
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'user_id.required' => 'Karyawan tidak boleh kosong',
            'user_id.integer' => 'Karyawan tidak valid',
            'user_id.exists' => 'Karyawan tidak valid',
            'date.required' => 'Karyawan tidak boleh kosong',
            'date.date' => 'Karyawan tidak valid',
            'spk_image.image' => 'File SPK Harus berupa gambar',
            'spk_image.mimes' => 'File SPK Harus berupa gambar',
            'spk_image.max' => 'File SPK Harus berukuran maksimal 2 Mb',
            'amount.required' => 'Karyawan tidak boleh kosong',
        ];
    }
}
