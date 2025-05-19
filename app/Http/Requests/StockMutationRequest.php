<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StockMutationRequest extends FormRequest
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
            'from_branch' => 'required',
            'to_branch' => 'required',
        ];
    }


    public function messages(): array
    {
        return [
            'from_branch.required' => 'Cabang asal tidak boleh kosong',
            'to_branch.required' => 'Cabang tujuan tidak boleh kosong',
        ];
    }
}
