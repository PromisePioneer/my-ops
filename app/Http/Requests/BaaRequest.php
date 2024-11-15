<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /** yang ditemukan
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'fab_id' => ['required', Rule::exists('fab', 'id')],
            'po_number' => ['required', Rule::unique('baa', 'po_number')],
            'work_location' => ['required'],
        ];
    }


    public function messages(): array
    {
        return [
            'date.required' => 'Tanggal tidak boleh kosong',
            'fab_id.required' => 'Fab tidak boleh kosong',
            'fab_id.exists' => 'Fab tidak ditemukan',
            'po_number.required' => 'No. PO tidak boleh kosong',
            'po_number.unique' => 'No. PO telah terdaftar',
            'work_location.required' => 'Lokasi tidak boleh kosong',
        ];
    }
}
