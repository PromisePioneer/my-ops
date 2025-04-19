<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UsedItemsRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'total_used' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'total_used.required' => 'total terpakai tidak boleh kosong.',
            'total_used.numeric' => 'total terpakai harus berupa angka.',
            'total_used.min' => 'total terpakai tidak boleh kurang dari 0.',
        ];
    }
}
