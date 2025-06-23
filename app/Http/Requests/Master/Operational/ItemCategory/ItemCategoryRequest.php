<?php

namespace App\Http\Requests\Master\Operational\ItemCategory;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ItemCategoryRequest extends FormRequest
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
            'name' => ['required'],
            'description' => ['required'],
            'notes' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Nama Kategori tidak boleh kosong.',
        ];
    }
}
