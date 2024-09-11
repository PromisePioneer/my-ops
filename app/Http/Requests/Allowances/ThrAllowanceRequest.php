<?php

namespace App\Http\Requests\Allowances;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ThrAllowanceRequest extends FormRequest
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
            'religion' => ['required', Rule::in('Islam', 'Kristen', 'Hindu', 'Buddha', 'Konghucu')],
            'date' => ['required', 'date'],
        ];
    }


    public function messages(): array
    {
        return [
            'religion.required' => 'Agama tidak boleh kosong',
            'religion.in' => 'Agama tidak valid',
            'date.required' => 'Tanggal tidak boleh kosong',
            'date.date' => 'Tanggal tidak valid',
        ];
    }
}
