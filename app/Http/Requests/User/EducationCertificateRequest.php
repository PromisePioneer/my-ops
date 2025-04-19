<?php

namespace App\Http\Requests\User;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EducationCertificateRequest extends FormRequest
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
            'organization' => ['required', 'string', 'max:255'],
            'year' => [
                'required',
                'digits:4',
                'integer',
                'min:1900',
                'max:'.Carbon::tomorrow()->year,
            ],
            'name' => ['required', 'string', 'max:255'],
            'file' => [
                'mimes:pdf,jpg,png,jpeg',
                'max:2048',
                Rule::requiredIf(function () use ($request) {
                    return $request->route('educationCertificate') === null;
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'organization.required' => 'Organisasi Penerbit tidak boleh kosong',
            'organization.max' => 'Organisasi Penerbit maksimal 255 karakter',
            'year.required' => 'Masa berlaku tidak boleh kosong',
            'year.digits' => 'Masa berlaku tidak valid',
            'year.integer' => 'Masa berlaku tidak valid',
            'year.min' => 'Masa berlaku tidak valid',
            'year.max' => 'Masa berlaku tidak valid',
            'name.required' => 'Nama Certificate tidak boleh kosong.',
            'name.max' => 'Nama sertifikat maksimal 255 karakter',
            'file.required_if' => 'File sertifikat tidak boleh kosong.',
            'file.mimes' => 'File sertifikat tidak valid.',
            'file.max' => 'File sertifikat maksimal 2MB',
        ];
    }
}
