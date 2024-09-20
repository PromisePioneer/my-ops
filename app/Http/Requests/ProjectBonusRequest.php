<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectBonusRequest extends FormRequest
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
            'date_active' => ['required', 'date'],
            'customer_name' => ['required', 'string'],
            'bast' => [
                'mimes:pdf',
                'max:2048',
                Rule::requiredIf(function () use ($request) {
                    return $request->route('projectBonus') === null;
                }),
            ],
            'baa' => [
                'mimes:pdf',
                'max:2048',
                Rule::requiredIf(function () use ($request) {
                    return $request->route('projectBonus') === null;
                }),
            ],
            'work_description' => ['required', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'date_active.required' => 'Tanggal Aktif harus diisi.',
            'date_active.date' => 'Tanggal Aktif harus berupa tanggal.',
            'customer_name.required' => 'Nama Customer harus diisi.',
            'baa.required' => 'BAA tidak boleh kosong',
            'baa.mimes' => 'BAA harus berupa pdf',
            'bast.required' => 'BAST tidak boleh kosong',
            'bast.mimes' => 'BAST harus berupa pdf',
            'work_description.required' => 'Deskripsi pekerjaan harus diisi.',
        ];
    }
}
