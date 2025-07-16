<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
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
            'code' => ['required'],
            'name' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'image' => [Rule::requiredIf($request->route('company')),
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048'
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'code.required' => 'Kode tidak boleh kosong.',
            'name.required' => 'Nama tidak boleh kosong.',
            'phone.required' => 'No. Telepon tidak boleh kosong.',
            'address.required' => 'Alamat tidak boleh kosong.',
            'image.required' => 'Foto tidak boleh kosong.',
            'image.image' => 'Foto harus berupa gambar.',
            'image.mimes' => 'Foto harus berupa gambar.',
            'image.max' => 'Ukuran gambar terlalu besar.',
        ];
    }
}
