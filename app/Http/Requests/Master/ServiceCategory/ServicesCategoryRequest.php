<?php

namespace App\Http\Requests\Master\ServiceCategory;

use Illuminate\Foundation\Http\FormRequest;

class ServicesCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'capacity' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'capacity.required' => 'Kapasitas tidak boleh kosong',
        ];
    }
}
