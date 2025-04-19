<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class JointClosureRequest extends FormRequest
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
            'code_id' => ['required', 'exists:joint_closures_code,id'],
            'region' => ['required'],
            'fo_cable_id' => ['required', 'exists:fo_cables,id'],
            'lat' => ['required', 'between:-90,90'],
            'long' => ['required', 'between:-180,180'],
            'cut_off_date' => ['required', 'date'],
        ];
    }


    public function messages(): array
    {
        return [
            'code_id.required' => 'Kode tidak boleh kosong',
            'code_id.exists' => 'Kode tidak valid',
            'region.required' => 'Region tidak boleh kosong',
            'fo_cable_id.required' => 'Fo Cable tidak boleh kosong',
            'fo_cable_id.exists' => 'Fo Cable tidak valid',
            'lat.required' => 'Latitude tidak boleh kosong',
            'lat.between' => 'Latitude tidak valid',
            'long.required' => 'Longitude tidak boleh kosong',
            'long.between' => 'Longitude tidak valid',
            'cut_off_date.required' => 'Cut Off Date tidak boleh kosong',
            'cut_off_date.date' => 'Cut Off Date tidak valid',
        ];
    }
}
