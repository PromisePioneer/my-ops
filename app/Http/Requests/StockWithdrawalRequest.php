<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StockWithdrawalRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'user_id' => ['required'],
            'description' => ['required', 'string'],
        ];
    }


    public function messages(): array
    {
        return [
            'description.required' => 'Deskripsi tidak boleh kosong',
            'user_id.required' => 'User tidak boleh kosong',
            'itemWithCodeFields.required' => 'Barang tidak boleh kosong apabila memilih barang dengan kode',
        ];
    }
}
