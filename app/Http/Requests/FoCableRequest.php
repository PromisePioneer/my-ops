<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FoCableRequest extends FormRequest
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
            'branch_id' => ['nullable'],
            'segment_id' => ['required'],
            'classification' => [
                'required',
                Rule::in('Backbone', 'Backhaul', 'Fronthaul', 'Akses'),
            ],
            'cable_placement' => ['required', Rule::in('Udara', 'Underground')],
            'cable_address' => ['required'],
            'starting_point_lat' => ['required', 'between:-90,90'],
            'starting_point_long' => ['required', 'between:-180,180'],
            'ending_point_lat' => ['required', 'between:-90,90'],
            'ending_point_long' => ['required', 'between:-180,180'],
            'length' => ['required'],
            'cut_off_date' => ['required', 'date'],
            'total_core' => ['required', 'numeric'],
            'used_core' => ['required', 'max:'.$request->total_core],
        ];
    }


    public function messages(): array
    {
        return [
            'segment_id.required' => ['Segmen tidak boleh kosong'],
            'classification.required' => ['Klasifikasi tidak boleh kosong'],
            'classification.in' => ['Klasifikasi tidak valid'],
            'cable_placement.required' => ['Letak Kabel tidak boleh kosong'],
            'cable_placement.in' => ['Letak Kabel tidak valid'],
            'starting_point_lat.required' => ['Latitude pada titik awal tidak boleh kosong'],
            'starting_point_lat.between' => ['Latitude pada titik awal tidak valid'],
            'starting_point_long.required' => ['Longitude pada titik awal tidak boleh kosong'],
            'starting_point_long.between' => ['Latitude pada titik awal tidak valid'],
            'ending_point_lat.required' => ['Latitude pada titik akhir tidak boleh kosong'],
            'ending_point_lat.between' => ['Latitude pada titik akhir tidak valid'],
            'ending_point_long.required' => ['Longitude pada titik akhir tidak boleh kosong'],
            'ending_point_long.between' => ['Latitude pada titik akhir tidak valid'],
            'length.required' => ['Panjang kabel tidak boleh kosong'],
            'cut_off_date.required' => ['Tanggal Cut Off tidak boleh kosong'],
            'cut_off_date.date' => ['Tanggal Cut Off tidak valid'],
            'cable_address.required' => ['Jalur Kabel tidak boleh kosong'],
            'total_core.required' => ['Jumlah Core tidak boleh kosong'],
        ];
    }
}
