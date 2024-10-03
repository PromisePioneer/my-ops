<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ODPRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(Request $request): array
    {
        return [
            'area_id' => ['required', Rule::exists('odp_areas', 'id')],
            'name' => ['required'],
            'classification' => ['required', Rule::in('AS', 'Turunan')],
            'passive_splitter' => ['required', Rule::in('ODP', 'FAT', 'ODU')],
            'lat' => [
                'required',
                'between:-90,90',
            ],
            'long' => [
                'required',
                'between:-180,180',
            ],
            'max_capacity' => ['required'],
            'used_capacity' => ['required', 'max:'.$request->input('max_capacity')],
            'cut_off_date' => ['required', 'date'],
        ];
    }


    public function messages(): array
    {
        return [
            'area_id.required' => 'Area tidak boleh kosong.',
            'area_id.exists' => 'Area tidak ditemukan.',
            'name.required' => 'Nama ODP tidak boleh kosong.',
            'classification.required' => 'Kelas ODP tidak boleh kosong.',
            'classification.in' => 'Kasifikasi ODP tidak valid.',
            'passive_splitter.required' => 'Passive Splitter tidak boleh kosong.',
            'passive_splitter.in' => 'Passive Splitter tidak valid.',
            'long.required' => 'Longitude tidak boleh kosong.',
            'long.between' => 'Longitude tidak valid.',
            'lat.required' => 'Latitude tidak boleh kosong.',
            'lat.between' => 'Latitude tidak valid.',
            'max_capacity.required' => 'Max Capacity tidak boleh kosong.',
            'cut_off_date' => 'Tanggal Cut Off tidak boleh kosong.',
            'used_capacity.required' => 'Kapasitas terpakai tidak boleh kosong.',
            'used_capacity.max' => 'Kapasitas terpakai tidak boleh lebih dari Maksimal kapasitas.',
        ];
    }
}
