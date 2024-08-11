<?php

namespace App\Http\Requests\User;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EducationRequest extends FormRequest
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
            'level' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'graduation_year' => [
                'required',
                'digits:4',
                'integer',
                'min:1900',
                'max:'.Carbon::tomorrow()->year
            ],
            'certificate_of_graduation' => [
                'mimes:pdf',
                'max:2048',
                Rule::requiredIf(function () use ($request) {
                    return $request->route('user') === null;
                })
            ],
            'gpa' => ['required', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'level.required' => 'tingkat pendidikan tidak boleh kosong',
            'institution.required' => 'instansi tidak boleh kosong',
            'major.required' => 'jurusan tidak boleh kosong',
            'graduation_year.required' => 'tahun lulus tidak boleh kosong',
            'graduation_year.digits' => 'tahun lulus tidak valid!',
            'certificate_of_graduation.required_if' => 'ijazah tidak boleh kosong',
            'certificate_of_graduation.mimes' => 'file harus berupa pdf',
            'certificate_of_graduation.max' => 'file harus berukuran maksimal 2 Mb',
            'gpa.required' => 'IPK atau nilai rata - rata tidak boleh kosong',
            'gpa.numeric' => 'IPK atau nilai rata - rata harus berupa angka',
        ];
    }
}
