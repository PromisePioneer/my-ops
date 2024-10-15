<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PoleRequest extends FormRequest
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
            'branch_id' => ['nullable', Rule::exists('branches', 'id')],
            'diameter' => ['required', 'integer'],
            'length' => ['required', 'integer'],
            'region' => ['required'],
            'code' => ['required'],
            'lat' => [
                'required',
                'between:-90,90',
            ],
            'long' => [
                'required',
                'between:-180,180',
            ],
            'cut_off_date' => ['required', 'date'],
        ];
    }


    public function messages(): array
    {
        return [
            'branch_id.exists' => 'Cabang tidak ditemukan',
            'diameter.required' => 'Diameter tidak boleh kosong',
            'length.required' => 'Length tidak boleh kosong',
            'region.required' => 'Region tidak boleh kosong',
            'code.required' => 'Kode tidak boleh kosong',
            'lat.required' => 'Latitude tidak boleh kosong',
            'long.required' => 'Longitude tidak boleh kosong',
            'cut_off_date.required' => 'Cutoff Date tidak boleh kosong',
            'cut_off_date.date' => 'Cut off Data tidak valid',
        ];
    }
}
